<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // --- Logika BARU untuk Dashboard Staf (Guru & Admin) ---

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

            $dayMap = [ 6 => 1, 0 => 2, 1 => 3, 2 => 4, 3 => 5, 4 => 6 ]; // Sabtu -> Kamis
            $todayAppDay = $dayMap[Carbon::now()->dayOfWeek] ?? null;

            $todaysSchedules = collect();
            if ($todayAppDay) {
                $todaysSchedules = Schedule::where('teacher_id', $teacher->id)
                    ->where('day_of_week', $todayAppDay)
                    ->with(['subject', 'kelas', 'room'])
                    ->orderBy('time_slot')
                    ->get()
                    ->keyBy('time_slot');
            }

            $scheduleSlots = [];
            for ($i = 1; $i <= 7; $i++) {
                $scheduleSlots[$i] = $todaysSchedules->get($i);
            }

            $viewData['scheduleSlots'] = $scheduleSlots;
            $viewData['todayDateString'] = Carbon::now()->translatedFormat('l, d F Y');
        }

        // 4. Kirim semua data yang sudah terkumpul ke view
        return view('dashboard', $viewData);
    }
}

