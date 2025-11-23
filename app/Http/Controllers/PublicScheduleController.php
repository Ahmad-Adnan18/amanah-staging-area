<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Schedule;
use App\Models\Teacher; // [PERBAIKAN] Kembali menggunakan model Teacher
use App\Models\User;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PublicScheduleController extends Controller
{
    public function index(): View
    {
        $classes = Kelas::where('is_active_for_scheduling', true)->orderBy('nama_kelas')->get();

        // [PERBAIKAN] Ambil data guru langsung dari tabel 'teachers'
        $teachers = Teacher::orderBy('name')->get();
        
        // [NEW] Ambil data pelajaran yang memiliki jadwal
        $subjectIds = Schedule::distinct('mata_pelajaran_id')->pluck('mata_pelajaran_id');
        $allSubjects = MataPelajaran::whereIn('id', $subjectIds)->get();
        
        // Ambil pelajaran unik berdasarkan nama_pelajaran
        $uniqueSubjects = [];
        $usedNames = [];
        
        foreach($allSubjects as $subject) {
            if (!in_array($subject->nama_pelajaran, $usedNames)) {
                $uniqueSubjects[] = $subject;
                $usedNames[] = $subject->nama_pelajaran;
            }
        }
        
        $subjects = collect($uniqueSubjects)->sortBy('nama_pelajaran')->values();
        
        // [NEW] Buat mapping nama pelajaran ke ID pelajaran
        $subjectNameToIds = [];
        foreach($allSubjects as $subject) {
            $subjectName = $subject->nama_pelajaran;
            if (!isset($subjectNameToIds[$subjectName])) {
                $subjectNameToIds[$subjectName] = [];
            }
            if (!in_array($subject->id, $subjectNameToIds[$subjectName])) {
                $subjectNameToIds[$subjectName][] = $subject->id;
            }
        }

        $schedules = Schedule::with(['subject', 'teacher', 'room', 'kelas'])->get();

        // [PERBAIKAN PENTING]
        // Pastikan 'byTeacher' dikelompokkan berdasarkan ID dari tabel 'teachers'
        $scheduleData = [
            'byClass' => $this->formatScheduleForJs($schedules, 'kelas_id'),
            'byTeacher' => $this->formatScheduleForJs($schedules, 'teacher_id'),
            'bySubject' => $this->formatScheduleForJsBySubject($schedules), // [NEW] Gunakan fungsi baru
            'teachingHours' => $this->calculateTeachingHours($schedules),
        ];

        // [NEW] Hitung guru yang libur
        $teachersDayOff = $this->calculateTeachersDayOff($schedules, $teachers);

        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);

        // [PERBAIKAN] Data guru untuk dropdown diubah agar value-nya adalah teacher->id
        // Namun, kita tetap menggunakan data $teachers yang sudah diambil di atas.
        // Penyesuaian akan dilakukan di JavaScript.
        return view('jadwal.public.index', compact('classes', 'teachers', 'subjects', 'scheduleData', 'days', 'timeSlots', 'teachersDayOff', 'subjectNameToIds'));
    }

    private function formatScheduleForJs($schedules, $key)
    {
        $formatted = [];
        foreach ($schedules as $schedule) {
            // Logika ini sekarang akan bekerja dengan benar
            if ($schedule->{$key} && $schedule->teacher) {
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
    
    // [NEW] Fungsi khusus untuk format jadwal pelajaran yang bisa menangani banyak jadwal di hari dan jam yang sama
    private function formatScheduleForJsBySubject($schedules)
    {
        $formatted = [];
        foreach ($schedules as $schedule) {
            if ($schedule->mata_pelajaran_id && $schedule->teacher) {
                // Membuat array untuk menangani banyak jadwal dalam satu hari dan jam
                if (!isset($formatted[$schedule->mata_pelajaran_id][$schedule->day_of_week][$schedule->time_slot])) {
                    $formatted[$schedule->mata_pelajaran_id][$schedule->day_of_week][$schedule->time_slot] = [];
                }
                
                $formatted[$schedule->mata_pelajaran_id][$schedule->day_of_week][$schedule->time_slot][] = [
                    'subject' => $schedule->subject->nama_pelajaran ?? 'N/A',
                    'teacher' => $schedule->teacher->name ?? 'N/A',
                    'class' => $schedule->kelas->nama_kelas ?? 'N/A',
                    'room' => $schedule->room->name ?? 'N/A',
                ];
            }
        }
        return $formatted;
    }

    private function calculateTeachingHours($schedules)
    {
        $teachingHours = [];
        foreach ($schedules as $schedule) {
            $teacherId = $schedule->teacher_id; // Ini sekarang adalah ID dari tabel teachers
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

    public function getTeachingHours($teacherId) // Sekarang ini benar-benar teacherId
    {
        $schedules = Schedule::where('teacher_id', $teacherId)->get();
        $teachingHours = ['sabtu' => 0, 'ahad' => 0, 'senin' => 0, 'selasa' => 0, 'rabu' => 0, 'kamis' => 0, 'total' => 0];
        $dayMap = [1 => 'sabtu', 2 => 'ahad', 3 => 'senin', 4 => 'selasa', 5 => 'rabu', 6 => 'kamis'];

        foreach ($schedules as $schedule) {
            if (isset($dayMap[$schedule->day_of_week])) {
                $dayName = $dayMap[$schedule->day_of_week];
                $teachingHours[$dayName]++;
                $teachingHours['total']++;
            }
        }
        return response()->json($teachingHours);
    }

    public function print($type, $id)
    {
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);
        $viewName = 'jadwal.public.print';
        $data = ['days' => $days, 'timeSlots' => $timeSlots];

        if ($type == 'kelas') {
            $kelas = Kelas::findOrFail($id);
            $schedules = Schedule::where('kelas_id', $id)->with(['subject', 'teacher', 'room'])->get()->groupBy(['day_of_week', 'time_slot']);

            $data['title'] = 'Kelas: ' . $kelas->nama_kelas;
            $data['schedules'] = $schedules;
            $data['type'] = 'kelas';

        } elseif ($type == 'guru') {
            // [PERBAIKAN UTAMA] Kembali mencari guru di tabel TEACHERS
            $teacher = Teacher::findOrFail($id);

            // Cari jadwal berdasarkan ID TEACHER tersebut
            $schedulesCollection = Schedule::where('teacher_id', $teacher->id)->with(['subject', 'kelas', 'room'])->get();
            $schedules = $schedulesCollection->groupBy(['day_of_week', 'time_slot']);

            $teachingHours = $this->getTeachingHours($teacher->id)->getData(true);

            $data['title'] = 'Jadwal Mengajar: ' . $teacher->name;
            $data['schedules'] = $schedules;
            $data['type'] = 'guru';
            $data['teacherName'] = $teacher->name;
            $data['teachingHoursSummary'] = $teachingHours;

        } elseif ($type == 'pelajaran') {
            // [NEW] Tambahkan penanganan untuk tipe pelajaran
            $selectedSubject = MataPelajaran::findOrFail($id);
            
            // Ambil semua jadwal untuk pelajaran-pelajaran dengan nama yang sama
            $allSubjectIds = MataPelajaran::where('nama_pelajaran', $selectedSubject->nama_pelajaran)
                ->pluck('id');
                
            $schedules = Schedule::whereIn('mata_pelajaran_id', $allSubjectIds)
                ->with(['subject', 'teacher', 'kelas', 'room'])
                ->get()
                ->groupBy(['day_of_week', 'time_slot']);

            $data['title'] = 'Jadwal Pelajaran: ' . $selectedSubject->nama_pelajaran;
            $data['schedules'] = $schedules;
            $data['type'] = 'pelajaran';
            $data['subjectName'] = $selectedSubject->nama_pelajaran;
            $data['subject'] = $selectedSubject; // [NEW] Tambahkan objek subject

        } else {
            abort(404);
        }

        $pdf = Pdf::loadView($viewName, $data)->setPaper('a4', 'landscape');
        return $pdf->stream('jadwal-' . $type . '-' . $id . '.pdf');
    }

    // [NEW] Hitung guru yang libur per hari berdasarkan jadwal
    private function calculateTeachersDayOff($schedules, $teachers)
    {
        $teachersDayOff = [];

        foreach ($teachers as $teacher) {
            $teacherSchedules = $schedules->where('teacher_id', $teacher->id);
            $daysWithSchedule = $teacherSchedules->pluck('day_of_week')->unique()->toArray();

            // Guru libur di hari yang tidak ada jadwal
            foreach (range(1, 6) as $day) {
                if (!in_array($day, $daysWithSchedule)) {
                    $teachersDayOff[$day][] = [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'reason' => 'Tidak ada jadwal mengajar'
                    ];
                }
            }
        }

        return $teachersDayOff;
    }

    // [NEW] Method untuk print guru libur
    public function printGuruLibur($day)
    {
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];

        if (!isset($days[$day])) {
            abort(404);
        }

        $teachers = Teacher::orderBy('name')->get();
        $schedules = Schedule::with(['subject', 'teacher', 'room', 'kelas'])->get();
        $teachersDayOff = $this->calculateTeachersDayOff($schedules, $teachers);

        $data = [
            'title' => 'Daftar Guru Libur - ' . $days[$day],
            'guruLibur' => $teachersDayOff[$day] ?? [],
            'hari' => $days[$day],
            'tanggal' => now()->format('d/m/Y')
        ];

        $pdf = Pdf::loadView('jadwal.public.print-guru-libur', $data)->setPaper('a4', 'portrait');
        return $pdf->stream('guru-libur-' . $days[$day] . '.pdf');
    }
    

}