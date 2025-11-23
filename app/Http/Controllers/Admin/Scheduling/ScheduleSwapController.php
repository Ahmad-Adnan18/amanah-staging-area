<?php

namespace App\Http\Controllers\Admin\Scheduling;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Schedule;
use App\Models\TeacherUnavailability;
use App\Models\BlockedTime;
use App\Models\HourPriority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleSwapController extends Controller
{
    public function showForm(Request $request)
    {
        $classes = Kelas::where('is_active_for_scheduling', true)->orderBy('nama_kelas')->get();
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);
        $schedules = Schedule::with(['subject', 'teacher', 'room', 'kelas'])->get();

        // Membangun struktur grid
        $grid = [];
        foreach ($classes as $class) {
            foreach ($days as $dayKey => $dayName) {
                foreach ($timeSlots as $timeSlot) {
                    $grid[$dayKey][$class->id][$timeSlot] = null;
                }
            }
        }

        foreach ($schedules as $schedule) {
            // [PERBAIKAN FINAL] Mengganti isset() dengan array_key_exists() yang lebih andal
            if (
                array_key_exists($schedule->day_of_week, $grid) &&
                array_key_exists($schedule->kelas_id, $grid[$schedule->day_of_week]) &&
                array_key_exists($schedule->time_slot, $grid[$schedule->day_of_week][$schedule->kelas_id])
            ) {
                $grid[$schedule->day_of_week][$schedule->kelas_id][$schedule->time_slot] = $schedule;
            }
        }

        $validationResult = session('validationResult');
        $sourceScheduleId = session('sourceScheduleId');
        $targetScheduleId = session('targetScheduleId');

        $sourceSchedule = $sourceScheduleId ? Schedule::find($sourceScheduleId) : null;
        $targetSchedule = $targetScheduleId ? Schedule::find($targetScheduleId) : null;

        // Mengirim semua variabel yang dibutuhkan ke view
        return view('admin.scheduling.swap.form', compact('classes', 'days', 'timeSlots', 'grid', 'schedules', 'validationResult', 'sourceScheduleId', 'targetScheduleId', 'sourceSchedule', 'targetSchedule'));
    }

    public function processSwap(Request $request)
    {
        $request->validate([
            'source_schedule_id' => 'required|exists:schedules,id',
            'target_schedule_id' => 'required|exists:schedules,id|different:source_schedule_id',
        ]);

        $source = Schedule::with('subject', 'teacher', 'kelas')->findOrFail($request->source_schedule_id);
        $target = Schedule::with('subject', 'teacher', 'kelas')->findOrFail($request->target_schedule_id);

        $validationResult = $this->validateSwap($source, $target);

        if ($request->has('confirm_swap') && $validationResult['isValid']) {

            DB::transaction(function () use ($source, $target) {
                $targetDay = $target->day_of_week;
                $targetSlot = $target->time_slot;

                // Saat menukar jadwal, room_id tetap harus sesuai dengan kelas masing-masing
                $sourceKelas = Kelas::find($source->kelas_id);
                $targetKelas = Kelas::find($target->kelas_id);

                $target->update([
                    'day_of_week' => $source->day_of_week, 
                    'time_slot' => $source->time_slot, 
                    'room_id' => $sourceKelas->room_id
                ]);
                $source->update([
                    'day_of_week' => $targetDay, 
                    'time_slot' => $targetSlot, 
                    'room_id' => $targetKelas->room_id
                ]);
            });

            return redirect()->route('admin.schedule.view.grid')->with('success', 'Jadwal berhasil ditukar.');
        }

        return redirect()->route('admin.schedule.swap.show')
            ->with('validationResult', $validationResult)
            ->with('sourceScheduleId', $source->id)
            ->with('targetScheduleId', $target->id)
            ->withInput();
    }

    private function validateSwap(Schedule $source, Schedule $target): array
    {
        $errors = [];

        // [PERBAIKAN] Validasi sekarang membandingkan tingkatan dari MATA PELAJARAN
        if ($source->subject->tingkatan !== $target->subject->tingkatan) {
            $errors[] = "Pertukaran jadwal hanya bisa dilakukan antar mata pelajaran dengan tingkatan yang sama.";
        } else {
            // Validasi lain hanya dijalankan jika tingkatannya sama
            
            if (!$this->isTeacherAvailable($source->teacher_id, $target->day_of_week, $target->time_slot)) {
                $errors[] = "{$source->teacher->name} tidak tersedia di slot target.";
            }
            if (!$this->isSlotValidForSubject($source->day_of_week, $source->time_slot, $target->subject)) {
                $errors[] = "Slot sumber tidak diizinkan untuk {$target->subject->nama_pelajaran}.";
            }
            if (!$this->isTeacherAvailable($target->teacher_id, $source->day_of_week, $source->time_slot)) {
                $errors[] = "{$target->teacher->name} tidak tersedia di slot sumber.";
            }
        }

        return ['isValid' => empty($errors), 'errors' => $errors, 'source' => $source, 'target' => $target];
    }

    private function isTeacherAvailable($teacherId, $day, $timeSlot): bool
    {
        return !TeacherUnavailability::where('teacher_id', $teacherId)
            ->where('day_of_week', $day)
            ->where('time_slot', $timeSlot)
            ->exists();
    }

    private function isSlotValidForSubject($day, $timeSlot, $subject): bool
    {
        if (!$subject)
            return false;

        if (BlockedTime::where('day_of_week', $day)->where('time_slot', $timeSlot)->exists()) {
            return false;
        }

        $priority = HourPriority::where('subject_category', $subject->kategori)
            ->where('day_of_week', $day)
            ->where('time_slot', $timeSlot)
            ->first();

        return $priority ? $priority->is_allowed : true;
    }
}

