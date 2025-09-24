<?php

namespace App\Services;

use App\Models\BlockedTime;
use App\Models\HourPriority;
use App\Models\Kelas;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\TeacherUnavailability;
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
    private array $scheduleGrid = [];
    private array $unplacedSubjects = [];
    private array $debugLog = [];

    public function run()
    {
        DB::beginTransaction();
        try {
            $this->initialize();
            
            if ($this->classes->isNotEmpty()) {
                $this->buildSchedule();
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
            $this->debugLog[] = "ERROR KRITIS: " . $e->getMessage();
            return [
                'success' => false,
                'unplaced' => $this->unplacedSubjects,
                'log' => $this->debugLog
            ];
        }
    }

    private function initialize(): void
    {
        $this->scheduleGrid = [];
        $this->unplacedSubjects = [];
        $this->debugLog = ['Memulai proses generator...'];

        Schedule::query()->delete();
        $this->debugLog[] = "Jadwal lama berhasil dihapus.";
        
        $this->classes = Kelas::where('is_active_for_scheduling', true)
                            ->with(['mataPelajarans', 'room'])
                            ->get();

        foreach ($this->classes as $class) {
            if (is_null($class->room)) {
                throw new Exception("Kelas '{$class->nama_kelas}' belum memiliki Ruangan Induk (Home Room). Harap tetapkan terlebih dahulu.");
            }
        }
        
        $this->rooms = Room::all();
        $this->teacherUnavailabilities = TeacherUnavailability::all()->groupBy('teacher_id');
        $this->hourPriorities = HourPriority::all();
        $this->blockedTimes = BlockedTime::all();

        $this->debugLog[] = "Data master berhasil dimuat: " . $this->classes->count() . " kelas, " . $this->rooms->count() . " ruangan.";
    }

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
        $reason = "";

        $isTeacherEverAvailable = false;
        for ($d = 1; $d <= 6; $d++) {
            for ($t = 1; $t <= 7; $t++) {
                if ($this->isTeacherAvailable($teacher->id, $d, $t)) {
                    $isTeacherEverAvailable = true;
                    break 2;
                }
            }
        }

        if (!$isTeacherEverAvailable) {
            $reason = "Guru '{$teacherName}' yang ditugaskan ditandai TIDAK TERSEDIA sepanjang minggu.";
        } else {
            if ($subject->requires_special_room) {
                 $reason = "Tidak ditemukan kombinasi slot waktu dan Ruangan Khusus yang cocok untuk jadwal guru '{$teacherName}'.";
            } else {
                 $homeRoomName = $class->room->name ?? 'N/A';
                 $reason = "Tidak ditemukan slot kosong di Ruangan Induk '{$homeRoomName}' yang cocok dengan jadwal guru '{$teacherName}'.";
            }
        }
        
        $this->debugLog[] = "GAGAL: {$reason}";
        $this->unplacedSubjects[] = "{$subject->nama_pelajaran} ({$className}) - Alasan: {$reason}";
    }

    private function getSubjectsForClass(Kelas $class): Collection
    {
        $subjects = new Collection();
        $allTeachers = Teacher::all()->keyBy('id');

        foreach ($class->mataPelajarans as $subject) {
            $assignedTeacherId = $subject->pivot->teacher_id;

            if ($assignedTeacherId && $allTeachers->has($assignedTeacherId)) {
                $teacher = $allTeachers->get($assignedTeacherId);
                for ($i = 0; $i < $subject->duration_jp; $i++) {
                    $subjects->push([
                        'subject' => $subject,
                        'teacher' => $teacher,
                    ]);
                }
            } else {
                $this->debugLog[] = "PERINGATAN: Mata pelajaran '{$subject->nama_pelajaran}' di kelas {$class->nama_kelas} dilewati karena belum ada guru yang dialokasikan.";
                $this->unplacedSubjects[] = "{$subject->nama_pelajaran} ({$class->nama_kelas}) - Alasan: Belum ada alokasi guru.";
            }
        }
        return $subjects->shuffle();
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

    private function isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $room): bool
    {
        if (!$room) return false;

        return !$this->isBlockedTime($day, $timeSlot) &&
               $this->isTeacherAvailable($teacher->id, $day, $timeSlot) &&
               !$this->isClassBusy($class->id, $day, $timeSlot) &&
               !$this->isTeacherBusy($teacher->id, $day, $timeSlot) &&
               !$this->isRoomBusy($room->id, $day, $timeSlot) &&
               !$this->isConsecutiveSubject($class->id, $subject->id, $day, $timeSlot) && // [ATURAN BARU] Pengecekan mapel berurutan
               $this->isHourPriorityAllowed($subject->kategori, $day, $timeSlot);
    }
    
    /**
     * [FUNGSI BARU]
     * Memeriksa apakah mata pelajaran yang sama dijadwalkan di jam sebelumnya
     * untuk kelas yang sama pada hari yang sama.
     */
    private function isConsecutiveSubject($classId, $subjectId, $day, $timeSlot): bool
    {
        // Jika ini jam pertama, tidak mungkin berurutan.
        if ($timeSlot == 1) {
            return false;
        }

        $previousSlot = $timeSlot - 1;

        // Periksa apakah ada jadwal di jam sebelumnya untuk kelas ini.
        if (isset($this->scheduleGrid[$day][$previousSlot]['class'][$classId])) {
            $previousSubjectId = $this->scheduleGrid[$day][$previousSlot]['class'][$classId]['mata_pelajaran_id'];
            
            // Jika ID mata pelajaran sama dengan yang sebelumnya, maka ini berurutan.
            if ($previousSubjectId == $subjectId) {
                return true; // Ya, ini adalah mata pelajaran berurutan.
            }
        }

        // Bukan mata pelajaran berurutan.
        return false;
    }

    private function placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $room): void
    {
        $this->scheduleGrid[$day][$timeSlot]['class'][$class->id] = [
            'mata_pelajaran_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'room_id' => $room->id,
            'kelas_id' => $class->id,
            'day_of_week' => $day,
            'time_slot' => $timeSlot
        ];
        $this->scheduleGrid[$day][$timeSlot]['teacher'][$teacher->id] = true;
        $this->scheduleGrid[$day][$timeSlot]['room'][$room->id] = true;
    }

    private function saveSchedule(): void
    {
        $schedules = [];
        foreach ($this->scheduleGrid as $day => $timeSlots) {
            foreach ($timeSlots as $timeSlot => $entries) {
                if (isset($entries['class'])) {
                    foreach ($entries['class'] as $classId => $scheduleData) {
                        $schedules[] = $scheduleData;
                    }
                }
            }
        }
        
        if (!empty($schedules)) {
            Schedule::insert($schedules);
            $this->debugLog[] = "Jadwal berhasil dibuat dan disimpan ke database.";
        } else {
            $this->debugLog[] = "PERINGATAN: Tidak ada jadwal yang berhasil dibuat untuk disimpan.";
        }
    }

    private function getAvailableRooms($subject): Collection
    {
        if ($subject->requires_special_room) {
            return $this->rooms->where('type', 'Khusus');
        }
        return $this->rooms->where('type', 'Biasa');
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

