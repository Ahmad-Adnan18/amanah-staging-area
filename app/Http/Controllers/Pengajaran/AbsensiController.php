<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Schedule;
use App\Models\Absensi;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Exports\LegerAbsensiExport; // Akan dibuat di langkah berikutnya
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LegerAbsensiPeriodikExport;

class AbsensiController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Absensi::class);

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $santris = collect();
        $selectedSchedule = null;

        if ($request->filled(['kelas_id', 'schedule_id', 'tanggal'])) {
            $santris = Santri::where('kelas_id', $request->kelas_id)
                ->with([
                    'absensis' => fn($query) => $query->where('schedule_id', $request->schedule_id)
                        ->where('tanggal', $request->tanggal),
                ])
                ->orderBy('nama')
                ->get();

            $selectedSchedule = Schedule::with('subject')->find($request->schedule_id);
        }

        return view('pengajaran.absensi.index', compact('kelasList', 'santris', 'selectedSchedule'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Absensi::class);

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal' => 'required|date',
            'absensis' => 'required|array',
            'absensis.*.santri_id' => 'required|exists:santris,id',
            'absensis.*.status' => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alfa'])],
            'absensis.*.keterangan' => 'nullable|string|max:255',
        ]);

        // Validasi teacher: hanya bisa input untuk jadwal mereka
        if (Auth::user()->role === 'teacher') {
            $schedule = Schedule::findOrFail($validated['schedule_id']);
            if ($schedule->teacher_id !== Auth::user()->teacher?->id) {
                return back()->withErrors(['schedule_id' => 'Jadwal ini bukan milik Anda.']);
            }
        }

        foreach ($validated['absensis'] as $data) {
            Absensi::updateOrCreate(
                [
                    'santri_id' => $data['santri_id'],
                    'schedule_id' => $validated['schedule_id'],
                    'tanggal' => $validated['tanggal'],
                ],
                [
                    'kelas_id' => $validated['kelas_id'],
                    'teacher_id' => Auth::user()->teacher?->id ?? null,
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function update(Request $request, Absensi $absensi)
    {
        //\Log::info('Update absensi called for ID: ' . $absensi->id); // Debugging

        $this->authorize('update', $absensi);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alfa'])],
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek jika user adalah teacher dan bukan pengajar jadwal ini
        if (in_array(Auth::user()->role, ['teacher', 'ustadz_umum', 'pengasuhan', 'kesehatan']) && $absensi->schedule->teacher_id !== Auth::user()->teacher?->id) {
            // \Log::error('Unauthorized: User ' . Auth::id() . ' attempted to update absensi ID: ' . $absensi->id);
            return response()->json(['error' => 'Jadwal ini bukan milik Anda.'], 403);
        }

        $absensi->update([
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'],
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Absensi berhasil diperbarui.']);
    }

    public function destroy(Absensi $absensi)
    {
        //\Log::info('Delete absensi called for ID: ' . $absensi->id); // Debugging

        $this->authorize('delete', $absensi);

        if (in_array(Auth::user()->role, ['pengajaran', 'ustadz_umum', 'pengasuhan', 'kesehatan']) && $absensi->schedule->teacher_id !== Auth::user()->teacher?->id) {
            // \Log::error('Unauthorized: User ' . Auth::id() . ' attempted to delete absensi ID: ' . $absensi->id);
            return response()->json(['error' => 'Jadwal ini bukan milik Anda.'], 403);
        }

        $absensi->delete();

        return response()->json(['message' => 'Absensi berhasil dihapus.']);
    }


    public function exportLeger(Request $request)
    {

        $this->authorize('viewAny', Absensi::class);

        $filters = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal' => 'required|date',
        ]);

        $kelas = Kelas::findOrFail($filters['kelas_id']);
        $schedule = Schedule::with('subject')->findOrFail($filters['schedule_id']);

        if (!$schedule->subject) {
            // \Log::error('Subject tidak ditemukan untuk schedule_id: ' . $filters['schedule_id']);
            return back()->withErrors(['schedule_id' => 'Mata pelajaran tidak ditemukan.']);
        }

        $fileName = "Leger Absensi - {$schedule->subject->nama_pelajaran} - {$kelas->nama_kelas} - {$filters['tanggal']}.xlsx";

        return Excel::download(new LegerAbsensiExport($filters, $kelas, $schedule), $fileName);
    }

    public function getSchedulesByKelas($kelasId)
    {
        $schedules = Schedule::where('kelas_id', $kelasId)
            ->with('subject')
            ->get()
            ->map(function ($schedule) {
                // Peta day_of_week (1-6) ke nama hari
                $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
                $dayName = $days[$schedule->day_of_week] ?? 'Tidak Diketahui';

                return [
                    'id' => $schedule->id,
                    'display' => "{$schedule->subject->nama_pelajaran} ({$dayName}: Jam Ke- {$schedule->time_slot})",
                ];
            });

        return response()->json($schedules);
    }

    public function laporanPeriodik(Request $request)
    {
        $this->authorize('viewAny', Absensi::class);

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $rekapData = collect();
        $summary = null;
        $filters = $request->only(['kelas_id', 'bulan', 'schedule_id']);

        // Ambil data kelas dan schedule SEKALI SAJA di awal
        $selectedKelas = null;
        $selectedSchedule = null;

        if ($request->filled(['kelas_id', 'bulan'])) {
            $selectedKelas = Kelas::find($request->kelas_id);

            if ($request->schedule_id) {
                $selectedSchedule = Schedule::with('subject')->find($request->schedule_id);
            }

            $startDate = \Carbon\Carbon::parse($request->bulan)->startOfMonth();
            $endDate = \Carbon\Carbon::parse($request->bulan)->endOfMonth();

            // Ambil semua santri di kelas tersebut
            $santris = Santri::where('kelas_id', $request->kelas_id)
                ->orderBy('nama')
                ->get();

            $rekapData = $santris->map(function ($santri) use ($startDate, $endDate, $request) {
                $absensi = Absensi::where('santri_id', $santri->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->when($request->schedule_id, function ($query, $scheduleId) {
                        return $query->where('schedule_id', $scheduleId);
                    })
                    ->get();

                $total = $absensi->count();
                $hadir = $absensi->where('status', 'hadir')->count();
                $presentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                return [
                    'id' => $santri->id,
                    'nama' => $santri->nama,
                    'nis' => $santri->nis,
                    'total' => $total,
                    'hadir' => $hadir,
                    'izin' => $absensi->where('status', 'izin')->count(),
                    'sakit' => $absensi->where('status', 'sakit')->count(),
                    'alfa' => $absensi->where('status', 'alfa')->count(),
                    'presentase' => $presentase,
                ];
            });

            // Hitung total pertemuan (unique tanggal)
            $totalPertemuan = Absensi::where('kelas_id', $request->kelas_id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->when($request->schedule_id, function ($query, $scheduleId) {
                    return $query->where('schedule_id', $scheduleId);
                })
                ->distinct('tanggal')
                ->count('tanggal');

            // Summary keseluruhan
            $summary = [
                'total_santri' => $rekapData->count(),
                'rata_rata_presentase' => $rekapData->avg('presentase'),
                'total_hadir' => $rekapData->sum('hadir'),
                'total_izin' => $rekapData->sum('izin'),
                'total_sakit' => $rekapData->sum('sakit'),
                'total_alfa' => $rekapData->sum('alfa'),
                'periode' => \Carbon\Carbon::parse($request->bulan)->translatedFormat('F Y'),
                'kelas' => $selectedKelas->nama_kelas ?? 'Kelas Tidak Ditemukan',
                'total_pertemuan' => $totalPertemuan,
                'selected_schedule' => $selectedSchedule,
            ];
        }

        return view('pengajaran.absensi.laporan-periodik', compact(
            'kelasList',
            'rekapData',
            'summary',
            'filters',
            'selectedKelas',
            'selectedSchedule'
        ));
    }

    public function exportPeriodik(Request $request)
    {
        $this->authorize('viewAny', Absensi::class);

        $filters = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'bulan' => 'required|date_format:Y-m',
            'schedule_id' => 'nullable|exists:schedules,id',
        ]);

        return Excel::download(new LegerAbsensiPeriodikExport($filters), 'laporan-periodik.xlsx');
    }
}
