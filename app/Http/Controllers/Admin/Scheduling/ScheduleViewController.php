<?php

namespace App\Http\Controllers\Admin\Scheduling;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleViewController extends Controller
{
    public function grid(): View
    {
        $classes = Kelas::where('is_active_for_scheduling', true)->orderBy('nama_kelas')->get();
        
        // [PERBAIKAN] Menggunakan definisi hari yang benar (Sabtu - Kamis)
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);

        $schedules = Schedule::with(['subject', 'teacher', 'room', 'kelas'])->get();

        $grid = [];
        foreach ($classes as $class) {
            foreach ($days as $dayKey => $dayName) {
                foreach ($timeSlots as $timeSlot) {
                    $grid[$class->id][$dayKey][$timeSlot] = null;
                }
            }
        }

        foreach ($schedules as $schedule) {
            if ($schedule->kelas_id && array_key_exists($schedule->kelas_id, $grid) && array_key_exists($schedule->day_of_week, $grid[$schedule->kelas_id]) && array_key_exists($schedule->time_slot, $grid[$schedule->kelas_id][$schedule->day_of_week])) {
                 $grid[$schedule->kelas_id][$schedule->day_of_week][$schedule->time_slot] = $schedule;
            }
        }
        
        return view('admin.scheduling.view.grid', compact('classes', 'days', 'timeSlots', 'grid'));
    }

    public function monitoring(): View
    {
        $teachers = \App\Models\Teacher::with(['schedules.subject', 'schedules.kelas'])
            ->withCount('schedules')
            ->orderBy('name')
            ->get();

        // Calculate statistics
        $totalTeachers = $teachers->count();
        $totalHours = $teachers->sum('schedules_count');
        $averageHours = $totalTeachers > 0 ? round($totalHours / $totalTeachers, 1) : 0;
        
        // Find teacher with max hours
        $maxHoursTeacher = $teachers->sortByDesc('schedules_count')->first();
        $maxHours = $maxHoursTeacher ? $maxHoursTeacher->schedules_count : 0;

        return view('admin.scheduling.view.monitoring', compact('teachers', 'totalTeachers', 'totalHours', 'averageHours', 'maxHours'));
    }
}

