<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        // Logika untuk Wali Santri (tidak berubah)
        if ($user->role === 'wali_santri') {
            $santri = $user->santri()->with(['kelas', 'perizinans', 'pelanggarans'])->first();
            if (!$santri) {
                return view('wali.pending');
            }
            return view('wali.dashboard', compact('santri'));
        }

        // --- Logika untuk Dashboard Staf (Guru & Admin) ---

        // 1. Siapkan data statistik yang akan dilihat oleh SEMUA staf
        $viewData = [
            'isTeacher' => false,
            'totalSantri' => Santri::count(),
            'totalSantriPutra' => Santri::where('jenis_kelamin', 'Putra')->count(),
            'totalSantriPutri' => Santri::where('jenis_kelamin', 'Putri')->count(),
            'totalIzinAktif' => Perizinan::where('status', 'aktif')->count(),
            'jumlahTerlambat' => Perizinan::where('status', 'aktif')->whereDate('tanggal_akhir', '<', today())->count(),
        ];

        // 2. Cek apakah user adalah seorang guru
        $teacher = $user->teacher;

        // 3. Jika user adalah guru, tambahkan data jadwal ke $viewData
        if ($teacher) {
            $viewData['isTeacher'] = true;
            $viewData['teacher'] = $teacher;

            // Cek apakah hari ini adalah hari Jumat (5 = Jumat dalam Carbon)
            $today = Carbon::now('Asia/Jakarta');
            $dayOfWeek = $today->dayOfWeek; // 0 = Minggu, 1 = Senin, ..., 5 = Jumat, 6 = Sabtu
            $isFriday = ($dayOfWeek == 5); // Jumat adalah hari libur

            // JAM MAP untuk notifikasi (WAJIB untuk JavaScript)
            $jamMap = [
                1 => ['start' => '07:00', 'end' => '07:45', 'label' => '07:00 - 07:45'],
                2 => ['start' => '07:45', 'end' => '08:30', 'label' => '07:45 - 08:30'],
                3 => ['start' => '09:00', 'end' => '09:45', 'label' => '09:00 - 09:45'],
                4 => ['start' => '09:45', 'end' => '10:30', 'label' => '09:45 - 10:30'],
                5 => ['start' => '11:00', 'end' => '11:45', 'label' => '11:00 - 11:45'],
                6 => ['start' => '11:45', 'end' => '12:30', 'label' => '11:45 - 12:30'],
                7 => ['start' => '14:15', 'end' => '15:00', 'label' => '14:15 - 15:00'],
            ];

            if ($isFriday) {
                // Jika hari Jumat, tidak ada jadwal
                $viewData['isHoliday'] = true;
                $viewData['scheduleSlots'] = array_fill(1, 7, null); // Array numerik kosong
                $viewData['jamMap'] = $jamMap;

                Log::info('Dashboard - Friday (Holiday)', [
                    'user_id' => $user->id,
                    'teacher_id' => $teacher->id,
                    'date' => $today->toDateString()
                ]);
            } else {
                // Mapping hari: Sabtu (6) -> 1, Minggu (0) -> 2, Senin (1) -> 3, Selasa (2) -> 4, Rabu (3) -> 5, Kamis (4) -> 6
                $dayMap = [6 => 1, 0 => 2, 1 => 3, 2 => 4, 3 => 5, 4 => 6];
                $todayAppDay = $dayMap[Carbon::now('Asia/Jakarta')->dayOfWeek] ?? null;

                $todaysSchedules = collect();
                if ($todayAppDay) {
                    $todaysSchedules = Schedule::where('teacher_id', $teacher->id)
                        ->where('day_of_week', $todayAppDay)
                        ->with(['subject', 'kelas', 'room'])
                        ->orderBy('time_slot')
                        ->get()
                        ->keyBy('time_slot');
                }

                // ✅ PERBAIKAN: Buat array numerik 1-7 yang konsisten
                $scheduleSlots = array_fill(1, 7, null); // Default: semua slot kosong

                // Isi slot yang ada jadwal
                foreach ($todaysSchedules as $timeSlot => $schedule) {
                    if (is_numeric($timeSlot) && $timeSlot >= 1 && $timeSlot <= 7) {
                        $scheduleSlots[(int) $timeSlot] = $schedule;
                    }
                }

                // Pastikan semua slot terisi (null atau schedule object)
                for ($i = 1; $i <= 7; $i++) {
                    if (!isset($scheduleSlots[$i])) {
                        $scheduleSlots[$i] = null;
                    }
                }

                $viewData['isHoliday'] = false;
                $viewData['scheduleSlots'] = $scheduleSlots;
                $viewData['jamMap'] = $jamMap;

                // DEBUG LOG untuk troubleshooting
                $scheduleCount = count(array_filter($scheduleSlots, function ($slot) {
                    return $slot !== null;
                }));

                Log::info('Dashboard - Teacher Schedule', [
                    'user_id' => $user->id,
                    'teacher_id' => $teacher->id,
                    'date' => $today->toDateString(),
                    'day_of_week' => $todayAppDay,
                    'total_schedules' => $todaysSchedules->count(),
                    'schedule_slots_filled' => $scheduleCount,
                    'schedule_slots_structure' => array_keys($scheduleSlots),
                    'sample_slot_1' => $scheduleSlots[1] ? 'HAS_DATA' : 'EMPTY',
                    'sample_slot_7' => $scheduleSlots[7] ? 'HAS_DATA' : 'EMPTY'
                ]);
            }

            $today = Carbon::now('Asia/Jakarta');
            $viewData['todayDateString'] = $today->locale('id')->translatedFormat('l, d F Y');
        } else {
            // Untuk non-guru, tetap kirim data kosong yang konsisten
            $viewData['isHoliday'] = false;
            $viewData['scheduleSlots'] = array_fill(1, 7, null);
            $viewData['jamMap'] = [
                1 => ['start' => '07:00', 'end' => '07:45', 'label' => '07:00 - 07:45'],
                2 => ['start' => '07:45', 'end' => '08:30', 'label' => '07:45 - 08:30'],
                3 => ['start' => '09:00', 'end' => '09:45', 'label' => '09:00 - 09:45'],
                4 => ['start' => '09:45', 'end' => '10:30', 'label' => '09:45 - 10:30'],
                5 => ['start' => '11:00', 'end' => '11:45', 'label' => '11:00 - 11:45'],
                6 => ['start' => '11:45', 'end' => '12:30', 'label' => '11:45 - 12:30'],
                7 => ['start' => '14:15', 'end' => '15:00', 'label' => '14:15 - 15:00'],
            ];
        }

        // 4. Kirim semua data yang sudah terkumpul ke view
        return view('dashboard', $viewData);
    }

    public function jadwalSaya()
    {
        $user = Auth::user();

        if (!$user->teacher) {
            abort(403, 'Anda bukan guru');
        }

        $teacher = $user->teacher;

        // Ambil semua jadwal guru ini
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->with(['subject', 'kelas', 'room'])
            ->orderBy('day_of_week')
            ->orderBy('time_slot')
            ->get();

        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);

        // Format data untuk tabel
        $scheduleData = [];
        foreach ($schedules as $schedule) {
            $scheduleData[$schedule->day_of_week][$schedule->time_slot] = [
                'subject' => $schedule->subject->nama_pelajaran ?? 'N/A',
                'class' => $schedule->kelas->nama_kelas ?? 'N/A',
                'room' => $schedule->room->name ?? 'N/A',
            ];
        }

        // Hitung total jam dan jam per hari
        $totalJam = $schedules->count();
        $jamPerHari = [];
        foreach ($days as $dayKey => $dayName) {
            $jamPerHari[$dayName] = $schedules->where('day_of_week', $dayKey)->count();
        }

        return view('jadwal.public.saya', compact(
            'teacher',
            'scheduleData',
            'days',
            'timeSlots',
            'totalJam',
            'jamPerHari',
            'schedules'
        ));
    }
}