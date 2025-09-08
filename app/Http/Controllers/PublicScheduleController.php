<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
// [DIHAPUS] use ArPHP\I18n\Arabic; tidak lagi digunakan

class PublicScheduleController extends Controller
{
    /**
     * Menampilkan halaman utama untuk melihat jadwal per kelas atau per guru.
     */
    public function index(): View
    {
        // ... (Kode di method ini tidak berubah) ...
        $classes = Kelas::where('is_active_for_scheduling', true)->orderBy('nama_kelas')->get();
        $teachers = User::where('role', '!=', 'wali_santri')->orderBy('name')->get();
        $schedules = Schedule::with(['subject', 'teacher', 'room', 'kelas'])->get();
        $scheduleData = [
            'byClass' => $this->formatScheduleForJs($schedules, 'kelas_id'),
            'byTeacher' => $this->formatScheduleForJs($schedules, 'teacher_id'),
            'teachingHours' => $this->calculateTeachingHours($schedules),
        ];
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);
        return view('jadwal.public.index', compact('classes', 'teachers', 'scheduleData', 'days', 'timeSlots'));
    }

    /**
     * Helper function untuk memformat data jadwal.
     */
    private function formatScheduleForJs($schedules, $key)
    {
        // ... (Kode di method ini tidak berubah) ...
        $formatted = [];
        foreach ($schedules as $schedule) {
            if ($schedule->{$key}) {
                $formatted[$schedule->{$key}][$schedule->day_of_week][$schedule->time_slot] = [
                    'subject' => $schedule->subject->nama_pelajaran ?? 'N/A',
                    'teacher' => $schedule->teacher->name ?? 'N/A',
                    'class' => $schedule->kelas->nama_kelas ?? 'N/A',
                    'room' => $schedule->room->name ?? 'N/A',
                ];
            }
        }
        return $formatted;
    }

    /**
     * Menghitung jumlah jam mengajar.
     */
    private function calculateTeachingHours($schedules)
    {
        // ... (Kode di method ini tidak berubah) ...
        $teachingHours = [];
        foreach ($schedules as $schedule) {
            $teacherId = $schedule->teacher_id;
            $dayOfWeek = $schedule->day_of_week;
            if (!isset($teachingHours[$teacherId])) {
                $teachingHours[$teacherId] = ['sabtu' => 0, 'ahad' => 0, 'senin' => 0, 'selasa' => 0, 'rabu' => 0, 'kamis' => 0, 'total' => 0];
            }
            $dayMap = [1 => 'sabtu', 2 => 'ahad', 3 => 'senin', 4 => 'selasa', 5 => 'rabu', 6 => 'kamis'];
            if (isset($dayMap[$dayOfWeek])) {
                $dayName = $dayMap[$dayOfWeek];
                $teachingHours[$teacherId][$dayName]++;
                $teachingHours[$teacherId]['total']++;
            }
        }
        return $teachingHours;
    }

    /**
     * API endpoint jam mengajar.
     */
    public function getTeachingHours($teacherId)
    {
        // ... (Kode di method ini tidak berubah) ...
        $schedules = Schedule::where('teacher_id', $teacherId)->get();
        $teachingHours = ['sabtu' => 0, 'ahad' => 0, 'senin' => 0, 'selasa' => 0, 'rabu' => 0, 'kamis' => 0, 'total' => 0];
        foreach ($schedules as $schedule) {
            $dayMap = [1 => 'sabtu', 2 => 'ahad', 3 => 'senin', 4 => 'selasa', 5 => 'rabu', 6 => 'kamis'];
            if (isset($dayMap[$schedule->day_of_week])) {
                $dayName = $dayMap[$schedule->day_of_week];
                $teachingHours[$dayName]++;
                $teachingHours['total']++;
            }
        }
        return response()->json($teachingHours);
    }
    
    /**
     * Membuat dan menampilkan jadwal dalam format PDF.
     */
    public function print($type, $id)
    {
        // [DIHAPUS] Semua kode yang berhubungan dengan ArPHP\I18n\Arabic dihapus dari sini.

        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);
        $viewName = 'jadwal.public.print';
        $data = [
            'days' => $days,
            'timeSlots' => $timeSlots,
        ];

        if ($type == 'kelas') {
            $kelas = Kelas::findOrFail($id);
            $schedules = Schedule::where('kelas_id', $id)->with(['subject', 'teacher', 'room'])->get()->groupBy(['day_of_week', 'time_slot']);
            
            $data['title'] = 'Jadwal Pelajaran Kelas: ' . $kelas->nama_kelas;
            $data['schedules'] = $schedules;
            $data['type'] = 'kelas';

        } elseif ($type == 'guru') {
            $teacher = User::findOrFail($id);
            $schedulesCollection = Schedule::where('teacher_id', $id)->with(['subject', 'kelas', 'room'])->get();
            $schedules = $schedulesCollection->groupBy(['day_of_week', 'time_slot']);
            
            $teachingHours = ['sabtu' => 0, 'ahad' => 0, 'senin' => 0, 'selasa' => 0, 'rabu' => 0, 'kamis' => 0, 'total' => 0];
            $dayMap = [1 => 'sabtu', 2 => 'ahad', 3 => 'senin', 4 => 'selasa', 5 => 'rabu', 6 => 'kamis'];
            foreach ($schedulesCollection as $schedule) {
                if (isset($dayMap[$schedule->day_of_week])) {
                    $dayName = $dayMap[$schedule->day_of_week];
                    $teachingHours[$dayName]++;
                    $teachingHours['total']++;
                }
            }
            
            $data['title'] = 'Jadwal Mengajar: ' . $teacher->name;
            $data['schedules'] = $schedules;
            $data['type'] = 'guru';
            $data['teacherName'] = $teacher->name;
            $data['teachingHoursSummary'] = $teachingHours;

        } else {
            abort(404);
        }
        
        $pdf = Pdf::loadView($viewName, $data)->setPaper('a4', 'landscape');
        return $pdf->stream('jadwal-' . $type . '-' . $id . '.pdf');
    }
}

