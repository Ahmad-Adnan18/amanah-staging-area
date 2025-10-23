<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\RekapanHarian;
use App\Models\Kelas;
use App\Models\Santri;
use App\Exports\RekapanHarianExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RekapanHarianController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['pengajaran', 'teacher', 'ustadz_umum', 'pengasuhan', 'kesehatan', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $selectedKelas = $request->kelas_id;
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');

        $santris = collect();
        $rekapanData = collect();

        if ($request->filled('kelas_id')) {
            // Ambil santri berdasarkan kelas
            $santris = Santri::where('kelas_id', $request->kelas_id)
                ->orderBy('nama')
                ->get();

            // Ambil rekapan yang sudah ada
            $rekapanData = RekapanHarian::where('kelas_id', $request->kelas_id)
                ->where('tanggal', $tanggal)
                ->get()
                ->keyBy('santri_id');
        }

        return view('pengajaran.rekapan-harian.index', compact(
            'kelasList',
            'santris',
            'rekapanData',
            'selectedKelas',
            'tanggal'
        ));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'pengajaran' && Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya pengajaran atau admin yang bisa membuat rekapan.');
        }

        // Debug: Log atau dump request data
        // \Log::info('Request data: ' . json_encode($request->all()));
        //dd($request->all()); // Uncomment untuk test, comment setelah selesai

        try {
            $validated = $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'tanggal' => 'required|date',
                'rekapan' => 'required|array',
                'rekapan.*.santri_id' => 'required|exists:santris,id',
                'rekapan.*.jam_1' => 'required|integer|between:0,3',
                'rekapan.*.jam_2' => 'required|integer|between:0,3',
                'rekapan.*.jam_3' => 'required|integer|between:0,3',
                'rekapan.*.jam_4' => 'required|integer|between:0,3',
                'rekapan.*.jam_5' => 'required|integer|between:0,3',
                'rekapan.*.jam_6' => 'required|integer|between:0,3',
                'rekapan.*.jam_7' => 'required|integer|between:0,3',
                'rekapan.*.keterangan' => 'nullable|string|max:500',
            ]);

            foreach ($validated['rekapan'] as $data) {
                RekapanHarian::updateOrCreate(
                    [
                        'santri_id' => $data['santri_id'],
                        'tanggal' => $validated['tanggal'],
                    ],
                    [
                        'kelas_id' => $validated['kelas_id'],
                        'jam_1' => $data['jam_1'],
                        'jam_2' => $data['jam_2'],
                        'jam_3' => $data['jam_3'],
                        'jam_4' => $data['jam_4'],
                        'jam_5' => $data['jam_5'],
                        'jam_6' => $data['jam_6'],
                        'jam_7' => $data['jam_7'],
                        'keterangan' => $data['keterangan'] ?? null,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]
                );
            }

            return redirect()->back()->with('success', 'Rekapan harian berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error saving rekapan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan rekapan: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        // Manual authorization check
        if (!in_array(Auth::user()->role, ['pengajaran', 'teacher', 'ustadz_umum', 'pengasuhan', 'kesehatan', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $rekapData = collect();
        $summary = null;
        $filters = $request->only(['kelas_id', 'periode', 'jenis_periode', 'periode_start', 'periode_end']);

        $jenisPeriode = $request->jenis_periode ?? 'harian';
        $periode = $request->periode ?? now()->format('Y-m-d');
        $periodeStart = $request->periode_start;
        $periodeEnd = $request->periode_end;

        if ($request->filled(['kelas_id'])) {
            $startDate = $endDate = null;
            $periodeLabel = '';

            // Tentukan rentang tanggal berdasarkan jenis periode
            switch ($jenisPeriode) {
                case 'harian':
                    $startDate = Carbon::parse($periode);
                    $endDate = Carbon::parse($periode);
                    $periodeLabel = $startDate->translatedFormat('d F Y');
                    break;

                case 'mingguan':
                    // Validasi untuk periode mingguan
                    if (!$request->filled(['periode_start', 'periode_end'])) {
                        // Default ke minggu ini jika tidak ada input
                        $startDate = now()->startOfWeek();
                        $endDate = now()->endOfWeek();
                        $periodeStart = $startDate->format('Y-m-d');
                        $periodeEnd = $endDate->format('Y-m-d');
                        $periodeLabel = $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y');
                    } else {
                        $startDate = Carbon::parse($periodeStart);
                        $endDate = Carbon::parse($periodeEnd);

                        // Validasi: end date tidak boleh sebelum start date
                        if ($endDate->lt($startDate)) {
                            return redirect()->back()->withErrors(['periode_end' => 'Tanggal akhir tidak boleh sebelum tanggal mulai.']);
                        }

                        $periodeLabel = $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y');
                    }
                    break;

                case 'bulanan':
                    $startDate = Carbon::parse($periode)->startOfMonth();
                    $endDate = Carbon::parse($periode)->endOfMonth();
                    $periodeLabel = $startDate->translatedFormat('F Y');
                    break;
            }

            // Ambil data rekapan
            $rekapData = RekapanHarian::where('kelas_id', $request->kelas_id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->with('santri')
                ->get()
                ->groupBy('santri_id')
                ->map(function ($rekapanSantri) {
                    $santri = $rekapanSantri->first()->santri;
                    $totalHadir = 0;
                    $totalJam = 0;

                    foreach ($rekapanSantri as $rekapan) {
                        for ($i = 1; $i <= 7; $i++) {
                            if ($rekapan->{"jam_$i"} !== RekapanHarian::STATUS_ALFA) {
                                $totalJam++;
                                if ($rekapan->{"jam_$i"} === RekapanHarian::STATUS_HADIR) {
                                    $totalHadir++;
                                }
                            }
                        }
                    }

                    $presentase = $totalJam > 0 ? round(($totalHadir / $totalJam) * 100, 1) : 0;

                    return [
                        'santri' => $santri,
                        'total_hadir' => $totalHadir,
                        'total_jam' => $totalJam,
                        'presentase' => $presentase,
                        'total_hari' => $rekapanSantri->count(),
                    ];
                })->values();

            // Hitung jumlah santri di kelas
            $totalSantri = Santri::where('kelas_id', $request->kelas_id)->count();

            // Hitung jumlah hari dalam periode
            $totalHari = $startDate->diffInDays($endDate) + 1;

            // Hitung summary
            $summary = [
                'total_santri' => $totalSantri,
                'rata_rata_presentase' => $rekapData->avg('presentase') ?? 0,
                'total_hari' => $totalHari,
                'periode_label' => $periodeLabel,
                'kelas' => Kelas::find($request->kelas_id)->nama_kelas ?? 'Kelas Tidak Ditemukan',
                'jenis_periode' => $jenisPeriode,
                'tanggal_mulai' => $startDate->translatedFormat('d F Y'),
                'tanggal_selesai' => $endDate->translatedFormat('d F Y'),
            ];
        }

        return view('pengajaran.rekapan-harian.laporan', compact(
            'kelasList',
            'rekapData',
            'summary',
            'filters',
            'jenisPeriode',
            'periode', // PASTIKAN INI ADA
            'periodeStart', // PASTIKAN INI ADA
            'periodeEnd' // PASTIKAN INI ADA
        ));
    }

    public function export(Request $request)
    {
        // Manual authorization check
        if (!in_array(Auth::user()->role, ['pengajaran', 'teacher', 'ustadz_umum', 'pengasuhan', 'kesehatan', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Debug: Log request data
        // \Log::info('Export Request Data:', $request->all());

        // Validasi yang lebih fleksibel
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_periode' => 'required|in:harian,mingguan,bulanan,keseluruhan',
            'periode' => 'nullable|string', // Ubah dari 'date' ke 'string' untuk handle bulanan (Y-m)
            'periode_start' => 'nullable|date',
            'periode_end' => 'nullable|date',
            'jenis_export' => 'required|in:excel,pdf',
        ]);

        $kelas = Kelas::findOrFail($validated['kelas_id']);
        $jenisPeriode = $validated['jenis_periode'];

        // Tentukan rentang tanggal berdasarkan jenis periode
        $startDate = null;
        $endDate = null;
        $periodeLabel = '';

        switch ($jenisPeriode) {
            case 'harian':
                // PERBAIKAN: Handle jika periode tidak ada, gunakan tanggal hari ini
                $periodeValue = $validated['periode'] ?? now()->format('Y-m-d');
                $startDate = Carbon::parse($periodeValue);
                $endDate = Carbon::parse($periodeValue);
                $periodeLabel = $startDate->translatedFormat('d F Y');
                break;

            case 'mingguan':
                // PERBAIKAN: Handle default values untuk mingguan
                $startDate = $validated['periode_start'] ? Carbon::parse($validated['periode_start']) : now()->startOfWeek();
                $endDate = $validated['periode_end'] ? Carbon::parse($validated['periode_end']) : now()->endOfWeek();
                $periodeLabel = $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y');
                break;

            case 'bulanan':
                // PERBAIKAN: Handle jika periode tidak ada, gunakan bulan ini
                $periodeValue = $validated['periode'] ?? now()->format('Y-m');
                $startDate = Carbon::parse($periodeValue . '-01')->startOfMonth();
                $endDate = Carbon::parse($periodeValue . '-01')->endOfMonth();
                $periodeLabel = $startDate->translatedFormat('F Y');
                break;

            case 'keseluruhan':
                $startDate = null;
                $endDate = null;
                $periodeLabel = 'Keseluruhan';
                break;
        }

        $filters = [
            'kelas_id' => $validated['kelas_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'jenis_periode' => $jenisPeriode,
            'periode_label' => $periodeLabel,
            'kelas_nama' => $kelas->nama_kelas,
        ];

        // Debug: Log filters
        // \Log::info('Export Filters:', $filters);

        $fileName = "Rekapan Kehadiran {$kelas->nama_kelas} - {$periodeLabel}." . ($validated['jenis_export'] === 'excel' ? 'xlsx' : 'pdf');

        if ($validated['jenis_export'] === 'excel') {
            return Excel::download(new RekapanHarianExport($filters), $fileName);
        } else {
            return $this->exportPdf($filters, $fileName);
        }
    }

    private function exportPdf($filters, $fileName)
    {
        // Ambil data untuk PDF
        $rekapData = $this->getRekapDataForExport($filters);
        $summary = $this->getSummaryForExport($rekapData, $filters);

        $pdf = \PDF::loadView('pengajaran.rekapan-harian.export-pdf', [
            'rekapData' => $rekapData,
            'summary' => $summary,
            'filters' => $filters,
        ]);

        return $pdf->download($fileName);
    }

    private function getRekapDataForExport($filters)
    {
        $query = RekapanHarian::where('kelas_id', $filters['kelas_id'])
            ->with('santri');

        if ($filters['start_date'] && $filters['end_date']) {
            $query->whereBetween('tanggal', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->get()
            ->groupBy('santri_id')
            ->map(function ($rekapanSantri) {
                $santri = $rekapanSantri->first()->santri;
                $totalHadir = 0;
                $totalJam = 0;

                foreach ($rekapanSantri as $rekapan) {
                    for ($i = 1; $i <= 7; $i++) {
                        if ($rekapan->{"jam_$i"} !== RekapanHarian::STATUS_ALFA) {
                            $totalJam++;
                            if ($rekapan->{"jam_$i"} === RekapanHarian::STATUS_HADIR) {
                                $totalHadir++;
                            }
                        }
                    }
                }

                $presentase = $totalJam > 0 ? round(($totalHadir / $totalJam) * 100, 1) : 0;

                return [
                    'santri' => $santri,
                    'total_hadir' => $totalHadir,
                    'total_jam' => $totalJam,
                    'presentase' => $presentase,
                    'total_hari' => $rekapanSantri->count(),
                ];
            })->values();
    }

    private function getSummaryForExport($rekapData, $filters)
    {
        $totalSantri = Santri::where('kelas_id', $filters['kelas_id'])->count();

        return [
            'total_santri' => $totalSantri,
            'rata_rata_presentase' => $rekapData->avg('presentase') ?? 0,
            'total_hari' => $filters['jenis_periode'] === 'keseluruhan' ?
                RekapanHarian::where('kelas_id', $filters['kelas_id'])->distinct('tanggal')->count() : ($filters['start_date']->diffInDays($filters['end_date']) + 1),
            'periode_label' => $filters['periode_label'],
            'kelas' => $filters['kelas_nama'],
            'jenis_periode' => $filters['jenis_periode'],
        ];
    }
}
