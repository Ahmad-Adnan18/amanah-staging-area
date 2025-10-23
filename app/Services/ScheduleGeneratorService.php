<?php

namespace App\Services;

use App\Models\BlockedTime;
use App\Models\HourPriority;
use App\Models\Kelas;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\TeacherUnavailability;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Exception;

class ScheduleGeneratorService
{
    private Collection $classes;
    private Collection $rooms;
    private Collection $teacherUnavailabilities;
    private Collection $hourPriorities;
    private Collection $blockedTimes;
    private Collection $allTeachers;
    private array $scheduleGrid = [];
    private array $unplacedSubjects = [];
    private array $debugLog = [];

    public function run(bool $clearExisting = false, string $strategy = 'incremental')
    {
        DB::beginTransaction();
        try {
            $this->initialize($clearExisting, $strategy);

            if ($this->classes->isNotEmpty()) {
                $this->buildSchedule($strategy);
                $this->saveSchedule();
            }

            DB::commit();

            return [
                'success' => empty($this->unplacedSubjects),
                'unplaced' => $this->unplacedSubjects,
                'log' => $this->debugLog
            ];
        } catch (Exception $e) {
            DB::rollBack();
            if (empty($this->debugLog['error'])) {
                $this->debugLog[] = "ERROR KRITIS: " . $e->getMessage();
            }

            return [
                'success' => false,
                'unplaced' => $this->unplacedSubjects,
                'log' => $this->debugLog
            ];
        }
    }

    private function initialize(bool $clearExisting = false, string $strategy = 'incremental'): void
    {
        $this->scheduleGrid = [];
        $this->unplacedSubjects = [];
        $this->debugLog = ['Memulai proses generator...'];

        if ($clearExisting) {
            Schedule::query()->delete();
            $this->debugLog[] = "Jadwal lama berhasil dihapus.";
        } else {
            $this->debugLog[] = "Mode hybrid - mempertahankan jadwal existing.";
            $this->loadExistingSchedules();
        }

        // Load data master lainnya...
        $this->classes = Kelas::where('is_active_for_scheduling', true)
            ->with(['mataPelajarans', 'room'])
            ->get();

        foreach ($this->classes as $class) {
            if (is_null($class->room)) {
                throw new Exception("Kelas '{$class->nama_kelas}' belum memiliki Ruangan Induk (Home Room). Harap tetapkan terlebih dahulu.");
            }
        }

        $this->rooms = Room::all();
        $this->allTeachers = Teacher::all()->keyBy('id');

        $validTeacherIds = $this->allTeachers->pluck('id')->all();
        $this->teacherUnavailabilities = TeacherUnavailability::whereIn('teacher_id', $validTeacherIds)
            ->get()
            ->groupBy('teacher_id');

        $this->hourPriorities = HourPriority::all();
        $this->blockedTimes = BlockedTime::all();

        $this->debugLog[] = "Data master berhasil dimuat: " . $this->classes->count() . " kelas, " . $this->rooms->count() . " ruangan.";
        $this->debugLog[] = "Strategy: {$strategy}";
    }

    private function loadExistingSchedules(): void
    {
        $existingSchedules = Schedule::all();

        foreach ($existingSchedules as $schedule) {
            $this->scheduleGrid[$schedule->day_of_week][$schedule->time_slot]['class'][$schedule->kelas_id] = [
                'mata_pelajaran_id' => $schedule->mata_pelajaran_id,
                'teacher_id' => $schedule->teacher_id,
                'room_id' => $schedule->room_id,
                'kelas_id' => $schedule->kelas_id,
                'day_of_week' => $schedule->day_of_week,
                'time_slot' => $schedule->time_slot
            ];
            $this->scheduleGrid[$schedule->day_of_week][$schedule->time_slot]['teacher'][$schedule->teacher_id] = true;
            $this->scheduleGrid[$schedule->day_of_week][$schedule->time_slot]['room'][$schedule->room_id] = true;
        }

        $this->debugLog[] = "Loaded " . $existingSchedules->count() . " existing schedules";
    }

    // Hapus semua pengecekan 'user_id' di getSubjectsForClass
    private function getSubjectsForClass(Kelas $class): Collection
    {
        $subjects = new Collection();
        foreach ($class->mataPelajarans as $subject) {
            $assignedTeacherId = $subject->pivot->teacher_id;
            if ($this->allTeachers->has($assignedTeacherId)) {
                $teacher = $this->allTeachers->get($assignedTeacherId);
                for ($i = 0; $i < $subject->duration_jp; $i++) {
                    $subjects->push(['subject' => $subject, 'teacher' => $teacher]);
                }
            } else {
                $this->debugLog[] = "PERINGATAN: Alokasi guru (ID: {$assignedTeacherId}) tidak ditemukan.";
            }
        }
        return $subjects->shuffle();
    }

    // Kembalikan penyimpanan ke teacher->id
    private function placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $room): void
    {
        $this->scheduleGrid[$day][$timeSlot]['class'][$class->id] = [
            'mata_pelajaran_id' => $subject->id,
            'teacher_id' => $teacher->id, // KEMBALI MENGGUNAKAN teacher->id
            'room_id' => $room->id,
            'kelas_id' => $class->id,
            'day_of_week' => $day,
            'time_slot' => $timeSlot
        ];
        $this->scheduleGrid[$day][$timeSlot]['teacher'][$teacher->id] = true;
        $this->scheduleGrid[$day][$timeSlot]['room'][$room->id] = true;
    }

    // Kembalikan pengecekan kesibukan ke teacher->id
    private function isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $room): bool
    {
        if (!$room)
            return false;
        return !$this->isBlockedTime($day, $timeSlot) &&
            $this->isTeacherAvailable($teacher->id, $day, $timeSlot) &&
            !$this->isClassBusy($class->id, $day, $timeSlot) &&
            !$this->isTeacherBusy($teacher->id, $day, $timeSlot) && // KEMBALI MENGGUNAKAN teacher->id
            !$this->isRoomBusy($room->id, $day, $timeSlot) &&
            !$this->isConsecutiveSubject($class->id, $subject->id, $day, $timeSlot) &&
            $this->isHourPriorityAllowed($subject->kategori, $day, $timeSlot);
    }

    // ...Sisa file sama dengan kode terakhir yang berhasil...
    // (buildSchedule, diagnoseFailure, isConsecutiveSubject, saveSchedule, dll)

    private function buildSchedule(): void
    {
        foreach ($this->classes as $class) {
            $className = $class->nama_kelas;
            $this->debugLog[] = "--- Memproses Kelas: {$className} (Ruangan Induk: {$class->room->name}) ---";

            $subjectsToPlace = $this->getSubjectsForClass($class);

            foreach ($subjectsToPlace as $subjectData) {
                $subject = $subjectData['subject'];
                $teacher = $subjectData['teacher'];
                $placed = false;

                $this->debugLog[] = "Mencoba menempatkan: {$subject->nama_pelajaran} (1 JP) oleh {$teacher->name}";

                $days = collect(range(1, 6))->shuffle();
                foreach ($days as $day) {
                    $timeSlots = collect(range(1, 7))->shuffle();
                    foreach ($timeSlots as $timeSlot) {
                        if ($this->tryPlaceSubject($class, $subject, $day, $timeSlot, $teacher)) {
                            $this->debugLog[] = "BERHASIL: Ditempatkan pada Hari {$day}, Jam ke-{$timeSlot}.";
                            $placed = true;
                            break 2;
                        }
                    }
                }

                if (!$placed) {
                    $this->diagnoseFailure($class, $subject, $teacher);
                }
            }
        }
    }

    private function diagnoseFailure($class, $subject, $teacher)
    {
        $className = $class->nama_kelas;
        $teacherName = $teacher->name;
        $reason = "Tidak ditemukan slot waktu yang cocok untuk guru '{$teacherName}' di kelas ini.";
        $this->debugLog[] = "GAGAL: {$reason}";
        $this->unplacedSubjects[] = "{$subject->nama_pelajaran} ({$className}) - Alasan: {$reason}";
    }

    private function tryPlaceSubject($class, $subject, $day, $timeSlot, $teacher): bool
    {
        if ($subject->requires_special_room) {
            $specialRooms = $this->getAvailableRooms($subject);
            foreach ($specialRooms->shuffle() as $room) {
                if ($this->isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $room)) {
                    $this->placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $room);
                    return true;
                }
            }
        } else {
            $homeRoom = $class->room;
            if ($this->isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $homeRoom)) {
                $this->placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $homeRoom);
                return true;
            }
        }

        return false;
    }

    private function isConsecutiveSubject($classId, $subjectId, $day, $timeSlot): bool
    {
        if ($timeSlot == 1)
            return false;
        $previousSlot = $timeSlot - 1;
        if (isset($this->scheduleGrid[$day][$previousSlot]['class'][$classId])) {
            return $this->scheduleGrid[$day][$previousSlot]['class'][$classId]['mata_pelajaran_id'] == $subjectId;
        }
        return false;
    }

    private function saveSchedule(): void
    {
        $schedules = [];
        foreach ($this->scheduleGrid as $day => $timeSlots) {
            foreach ($timeSlots as $entries) {
                if (isset($entries['class'])) {
                    foreach ($entries['class'] as $scheduleData) {
                        $schedules[] = $scheduleData;
                    }
                }
            }
        }

        if (empty($schedules)) {
            $this->debugLog[] = "PERINGATAN: Tidak ada jadwal yang berhasil dibuat untuk disimpan.";
            return;
        }

        $this->debugLog[] = "Memulai penyimpanan jadwal satu per satu...";
        foreach ($schedules as $scheduleData) {
            try {
                Schedule::create($scheduleData);
            } catch (QueryException $e) {
                $this->debugLog['error'] = true;
                $this->debugLog[] = "ERROR: Gagal menyimpan data berikut ke database:";
                $this->debugLog[] = json_encode($scheduleData);
                throw $e;
            }
        }

        $this->debugLog[] = "Jadwal berhasil dibuat dan disimpan ke database.";
    }

    private function getAvailableRooms($subject): Collection
    {
        return $this->rooms->where('type', $subject->requires_special_room ? 'Khusus' : 'Biasa');
    }

    private function isTeacherAvailable($teacherId, $day, $timeSlot): bool
    {
        if (isset($this->teacherUnavailabilities[$teacherId])) {
            foreach ($this->teacherUnavailabilities[$teacherId] as $unavailability) {
                if ($unavailability->day_of_week == $day && $unavailability->time_slot == $timeSlot) {
                    return false;
                }
            }
        }
        return true;
    }

    private function isClassBusy($classId, $day, $timeSlot): bool
    {
        return isset($this->scheduleGrid[$day][$timeSlot]['class'][$classId]);
    }

    private function isTeacherBusy($teacherId, $day, $timeSlot): bool
    {
        return isset($this->scheduleGrid[$day][$timeSlot]['teacher'][$teacherId]);
    }

    private function isRoomBusy($roomId, $day, $timeSlot): bool
    {
        return isset($this->scheduleGrid[$day][$timeSlot]['room'][$roomId]);
    }

    private function isHourPriorityAllowed($category, $day, $timeSlot): bool
    {
        $priority = $this->hourPriorities->where('subject_category', $category)->where('day_of_week', $day)->where('time_slot', $timeSlot)->first();
        return $priority ? $priority->is_allowed : true;
    }

    private function isBlockedTime($day, $timeSlot): bool
    {
        return $this->blockedTimes->where('day_of_week', $day)->where('time_slot', $timeSlot)->isNotEmpty();
    }
}