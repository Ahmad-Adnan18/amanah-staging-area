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
    private Collection $allTeachers;
    private array $scheduleGrid = [];
    private array $unplacedSubjects = [];
    private array $forcedPlacements = [];
    private array $debugLog = [];
    private array $capacityWarnings = [];
    private float $startTime;

    private const MAX_EXECUTION_SECONDS = 25;
    private const MAX_RETRY_ATTEMPTS = 10; // Increased for better optimization
    private const TOTAL_DAYS = 6;
    private const TOTAL_SLOTS = 7;
    private const MAX_SLOTS_PER_CLASS = 42;

    public function run(bool $clearExisting = false, string $strategy = 'incremental')
    {
        $this->startTime = microtime(true);

        try {
            $this->loadMasterData($clearExisting);
            $this->runCapacityAnalysis();

            if ($this->classes->isEmpty()) {
                return $this->buildResult();
            }

            // Multiple retry
            $bestResult = null;
            $bestUnplacedCount = PHP_INT_MAX;
            $bestForcedCount = PHP_INT_MAX;

            for ($attempt = 1; $attempt <= self::MAX_RETRY_ATTEMPTS; $attempt++) {
                if ($this->isTimeoutApproaching()) break;

                $this->scheduleGrid = [];
                $this->unplacedSubjects = [];
                $this->forcedPlacements = [];

                $this->buildScheduleByTeacher();

                $unplacedCount = count($this->unplacedSubjects);
                $forcedCount = count($this->forcedPlacements);
                $this->debugLog[] = "Attempt {$attempt}: {$unplacedCount} gagal, {$forcedCount} forced";

                // Compare: prioritize fewer unplaced, then fewer forced
                $isBetter = ($unplacedCount < $bestUnplacedCount) ||
                            ($unplacedCount === $bestUnplacedCount && $forcedCount < $bestForcedCount);

                if ($isBetter) {
                    $bestUnplacedCount = $unplacedCount;
                    $bestForcedCount = $forcedCount;
                    $bestResult = [
                        'grid' => $this->scheduleGrid,
                        'unplaced' => $this->unplacedSubjects,
                        'forced' => $this->forcedPlacements,
                    ];

                    // Perfect result: 0 unplaced AND 0 forced
                    if ($unplacedCount === 0 && $forcedCount === 0) {
                        $this->debugLog[] = "Sempurna! (0 gagal, 0 forced)";
                        break;
                    }
                }
            }

            if ($bestResult) {
                $this->scheduleGrid = $bestResult['grid'];
                $this->unplacedSubjects = $bestResult['unplaced'];
                $this->forcedPlacements = $bestResult['forced'];
            }

            $this->debugLog[] = "Hasil terbaik: {$bestUnplacedCount} gagal, {$bestForcedCount} forced";

            // Save
            DB::beginTransaction();
            if ($clearExisting) Schedule::query()->delete();
            $this->saveSchedule();
            DB::commit();

            $elapsed = round(microtime(true) - $this->startTime, 2);
            $this->debugLog[] = "Selesai dalam {$elapsed}s";

            return $this->buildResult();

        } catch (Exception $e) {
            DB::rollBack();
            $this->debugLog[] = "ERROR: " . $e->getMessage();
            return [
                'success' => false,
                'unplaced' => $this->unplacedSubjects,
                'capacity_warnings' => $this->capacityWarnings,
                'forced_placements' => $this->forcedPlacements,
                'log' => $this->debugLog
            ];
        }
    }

    private function buildResult(): array
    {
        return [
            'success' => empty($this->unplacedSubjects),
            'unplaced' => $this->unplacedSubjects,
            'capacity_warnings' => $this->capacityWarnings,
            'forced_placements' => $this->forcedPlacements,
            'log' => $this->debugLog
        ];
    }

    private function runCapacityAnalysis(): void
    {
        $teacherLoad = [];
        $classLoad = []; // NEW: Track per-class JP
        $blockedSlotsCount = $this->blockedTimes->count();
        $maxPossibleSlots = self::MAX_SLOTS_PER_CLASS - $blockedSlotsCount;

        $this->debugLog[] = "Slot tersedia per kelas: {$maxPossibleSlots} (42 - {$blockedSlotsCount} blocked)";

        foreach ($this->classes as $class) {
            $classJp = 0;

            foreach ($class->mataPelajarans as $subject) {
                $teacherId = $subject->pivot->teacher_id;
                $jp = $subject->duration_jp;
                $classJp += $jp;

                if (!isset($teacherLoad[$teacherId])) {
                    $teacher = $this->allTeachers->get($teacherId);
                    $unavailCount = isset($this->teacherUnavailabilities[$teacherId])
                        ? count($this->teacherUnavailabilities[$teacherId])
                        : 0;

                    $teacherLoad[$teacherId] = [
                        'name' => $teacher ? $teacher->name : "ID:{$teacherId}",
                        'jp_needed' => 0,
                        'slots_available' => $maxPossibleSlots - $unavailCount
                    ];
                }
                $teacherLoad[$teacherId]['jp_needed'] += $jp;
            }

            // Store class JP count
            $classLoad[$class->id] = [
                'name' => $class->nama_kelas,
                'jp' => $classJp,
                'available' => $maxPossibleSlots
            ];

            // Log and warn if class has too many JP
            if ($classJp > $maxPossibleSlots) {
                $over = $classJp - $maxPossibleSlots;
                $this->capacityWarnings[] = "KELAS OVERLOAD: {$class->nama_kelas} punya {$classJp} JP tapi hanya {$maxPossibleSlots} slot (lebih {$over})";
            }

            $this->debugLog[] = "Kelas {$class->nama_kelas}: {$classJp} JP";
        }

        foreach ($teacherLoad as $data) {
            if ($data['jp_needed'] > $data['slots_available']) {
                $overload = $data['jp_needed'] - $data['slots_available'];
                $this->capacityWarnings[] = "GURU OVERLOAD: {$data['name']} butuh {$data['jp_needed']} JP, tersedia {$data['slots_available']} (lebih {$overload})";
            }
        }
    }

    private function isTimeoutApproaching(): bool
    {
        return (microtime(true) - $this->startTime) > self::MAX_EXECUTION_SECONDS;
    }

    private function loadMasterData(bool $clearExisting): void
    {
        $this->scheduleGrid = [];
        $this->unplacedSubjects = [];
        $this->forcedPlacements = [];
        $this->capacityWarnings = [];
        $this->debugLog = ['Generator (Multi-Retry + Soft Unavailability)...'];

        $this->classes = Kelas::where('is_active_for_scheduling', true)
            ->with(['mataPelajarans', 'room'])
            ->get();

        foreach ($this->classes as $class) {
            if (is_null($class->room)) {
                throw new Exception("Kelas '{$class->nama_kelas}' belum memiliki Ruangan Induk.");
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

        $this->debugLog[] = "Data: {$this->classes->count()} kelas, {$this->allTeachers->count()} guru";
    }

    private function buildScheduleByTeacher(): void
    {
        $teacherAssignments = [];

        foreach ($this->classes as $class) {
            foreach ($class->mataPelajarans as $subject) {
                $teacherId = $subject->pivot->teacher_id;
                if (!$this->allTeachers->has($teacherId)) continue;

                $teacher = $this->allTeachers->get($teacherId);

                if (!isset($teacherAssignments[$teacherId])) {
                    // Count unavailability for this teacher
                    $unavailCount = isset($this->teacherUnavailabilities[$teacherId])
                        ? count($this->teacherUnavailabilities[$teacherId])
                        : 0;

                    $teacherAssignments[$teacherId] = [
                        'teacher' => $teacher,
                        'unavail_count' => $unavailCount,
                        'assignments' => []
                    ];
                }

                for ($i = 0; $i < $subject->duration_jp; $i++) {
                    $teacherAssignments[$teacherId]['assignments'][] = [
                        'class' => $class,
                        'subject' => $subject,
                    ];
                }
            }
        }

        // Sort by unavailability count DESCENDING (most unavailable = hardest to schedule = first)
        uasort($teacherAssignments, function ($a, $b) {
            return $b['unavail_count'] <=> $a['unavail_count'];
        });

        $this->debugLog[] = "Urutan guru: paling banyak unavailable duluan";

        foreach ($teacherAssignments as $data) {
            if ($this->isTimeoutApproaching()) break;

            $teacher = $data['teacher'];
            $assignments = collect($data['assignments'])->shuffle();

            foreach ($assignments as $assignment) {
                $class = $assignment['class'];
                $subject = $assignment['subject'];
                $placed = false;

                // PASS 1: Respect all constraints
                $availableSlots = $this->getTeacherAvailableSlots($teacher, true);
                shuffle($availableSlots);

                foreach ($availableSlots as $slot) {
                    if ($this->tryPlaceSubject($class, $subject, $slot['day'], $slot['slot'], $teacher, true)) {
                        $placed = true;
                        break;
                    }
                }

                // PASS 2: Ignore hour priority
                if (!$placed) {
                    foreach ($availableSlots as $slot) {
                        if ($this->tryPlaceSubject($class, $subject, $slot['day'], $slot['slot'], $teacher, false)) {
                            $placed = true;
                            break;
                        }
                    }
                }

                // PASS 3: FORCED - Ignore teacher unavailability too
                if (!$placed) {
                    $allSlots = $this->getTeacherAvailableSlots($teacher, false); // false = ignore unavailability
                    shuffle($allSlots);

                    foreach ($allSlots as $slot) {
                        if ($this->tryPlaceSubjectForced($class, $subject, $slot['day'], $slot['slot'], $teacher)) {
                            $placed = true;
                            $this->forcedPlacements[] = "{$subject->nama_pelajaran} ({$class->nama_kelas}) - Guru {$teacher->name} di slot unavailable";
                            break;
                        }
                    }
                }

                if (!$placed) {
                    // Detailed diagnosis
                    $classBusyCount = 0;
                    $teacherBusyCount = 0;
                    $roomBusyCount = 0;
                    $blockedCount = 0;
                    $room = $class->room;

                    for ($d = 1; $d <= self::TOTAL_DAYS; $d++) {
                        for ($s = 1; $s <= self::TOTAL_SLOTS; $s++) {
                            if ($this->isBlockedTime($d, $s)) {
                                $blockedCount++;
                            } elseif ($this->isClassBusy($class->id, $d, $s)) {
                                $classBusyCount++;
                            } elseif ($this->isTeacherBusy($teacher->id, $d, $s)) {
                                $teacherBusyCount++;
                            } elseif ($room && $this->isRoomBusy($room->id, $d, $s)) {
                                $roomBusyCount++;
                            }
                        }
                    }

                    $detail = "Blocked:{$blockedCount}, ClassBusy:{$classBusyCount}, TeacherBusy:{$teacherBusyCount}, RoomBusy:{$roomBusyCount}";
                    $this->unplacedSubjects[] = "{$subject->nama_pelajaran} ({$class->nama_kelas}) - Guru {$teacher->name} [{$detail}]";
                }
            }
        }
    }

    private function getTeacherAvailableSlots($teacher, bool $respectUnavailability = true): array
    {
        $slots = [];

        for ($day = 1; $day <= self::TOTAL_DAYS; $day++) {
            for ($slot = 1; $slot <= self::TOTAL_SLOTS; $slot++) {
                if ($this->isBlockedTime($day, $slot)) continue;

                // Only check unavailability if we're respecting it
                if ($respectUnavailability && !$this->isTeacherAvailable($teacher->id, $day, $slot)) continue;

                $slots[] = ['day' => $day, 'slot' => $slot];
            }
        }

        return $slots;
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

    private function isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $room, bool $enforceHourPriority = true): bool
    {
        if (!$room) return false;

        $basicChecks = !$this->isBlockedTime($day, $timeSlot) &&
            $this->isTeacherAvailable($teacher->id, $day, $timeSlot) &&
            !$this->isClassBusy($class->id, $day, $timeSlot) &&
            !$this->isTeacherBusy($teacher->id, $day, $timeSlot) &&
            !$this->isRoomBusy($room->id, $day, $timeSlot);

        if (!$basicChecks) return false;

        if ($enforceHourPriority) {
            return $this->isHourPriorityAllowed($subject->kategori, $day, $timeSlot);
        }

        return true;
    }

    /**
     * Forced placement - ignores teacher unavailability but checks other constraints
     */
    private function isSlotAvailableForced($class, $subject, $day, $timeSlot, $teacher, $room): bool
    {
        if (!$room) return false;

        // Only check: not blocked, class not busy, teacher not already teaching, room not busy
        return !$this->isBlockedTime($day, $timeSlot) &&
            !$this->isClassBusy($class->id, $day, $timeSlot) &&
            !$this->isTeacherBusy($teacher->id, $day, $timeSlot) &&
            !$this->isRoomBusy($room->id, $day, $timeSlot);
    }

    private function tryPlaceSubject($class, $subject, $day, $timeSlot, $teacher, bool $enforceHourPriority = true): bool
    {
        if ($subject->requires_special_room) {
            foreach ($this->rooms->where('type', 'Khusus')->shuffle() as $room) {
                if ($this->isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $room, $enforceHourPriority)) {
                    $this->placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $room);
                    return true;
                }
            }
        } else {
            $homeRoom = $class->room;
            if ($this->isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $homeRoom, $enforceHourPriority)) {
                $this->placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $homeRoom);
                return true;
            }
        }

        return false;
    }

    private function tryPlaceSubjectForced($class, $subject, $day, $timeSlot, $teacher): bool
    {
        if ($subject->requires_special_room) {
            foreach ($this->rooms->where('type', 'Khusus')->shuffle() as $room) {
                if ($this->isSlotAvailableForced($class, $subject, $day, $timeSlot, $teacher, $room)) {
                    $this->placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $room);
                    return true;
                }
            }
        } else {
            $homeRoom = $class->room;
            if ($this->isSlotAvailableForced($class, $subject, $day, $timeSlot, $teacher, $homeRoom)) {
                $this->placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $homeRoom);
                return true;
            }
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

        if (empty($schedules)) return;

        $this->debugLog[] = "Menyimpan " . count($schedules) . " jadwal...";
        foreach ($schedules as $scheduleData) {
            Schedule::create($scheduleData);
        }
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