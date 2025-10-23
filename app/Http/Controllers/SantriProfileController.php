<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RaporSantriExport;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class SantriProfileController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan halaman profil lengkap seorang santri.
     */
    public function show(Santri $santri)
    {
        // Otorisasi: Semua role yang bisa melihat daftar santri, bisa melihat profilnya.
        $this->authorize('view', $santri);

        // Ambil data relasi untuk ditampilkan di tab
        $santri->load([
            'kelas',
            'perizinans' => function ($query) {
                $query->withTrashed()->latest(); // 🔥 Tambahkan withTrashed() di sini
            },
            'pelanggarans' => function ($query) {
                $query->latest();
            },
            'catatanHarians' => fn($q) => $q->with('pencatat')->latest(),
            'prestasis' => fn($q) => $q->with('pencatat')->latest(),
        ]);

        // [LOGIKA BARU] Mencari nama Wali Kelas
        $namaWaliKelas = null;
        if ($santri->jenis_kelamin && $santri->kelas) {
            $jabatanWali = 'Wali Kelas ' . $santri->jenis_kelamin; // Hasil: "Wali Kelas Putra" atau "Wali Kelas Putri"
            $waliKelas = $santri->kelas->penanggungJawab()
                ->where('tahun_ajaran', date('Y') . '/' . (date('Y') + 1)) // Asumsi tahun ajaran saat ini
                ->whereHas('jabatan', fn($q) => $q->where('nama_jabatan', $jabatanWali))
                ->with('user')
                ->first();
            if ($waliKelas) {
                $namaWaliKelas = $waliKelas->user->name;
            }
        }

        // Ambil dan kelompokkan data nilai akademik secara terpisah
        $nilaiAkademik = Nilai::where('santri_id', $santri->id)
            ->with('mataPelajaran')
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get()
            ->groupBy(['tahun_ajaran', 'semester']);

        return view('santri-profile.show', compact('santri', 'nilaiAkademik', 'namaWaliKelas'));
    }

    public function listForPortofolio(Request $request)
    {
        $this->authorize('viewAny', Santri::class);

        $query = Santri::with('kelas');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhere('nis', 'like', "%{$searchTerm}%");
            });
        }

        $santris = $query->orderBy('nama')->paginate(20)->withQueryString();

        return view('pengajaran.santri.portofolio-list', compact('santris'));
    }

    public function portofolio(Santri $santri)
    {
        $this->authorize('view', $santri);

        // Load semua data yang diperlukan untuk portofolio
        $santri->load([
            'perizinans',
            'pelanggarans',
            'prestasis.pencatat',
            'riwayatPenyakits.pencatat',
            'catatanHarians.pencatat',
            'kelas'
        ]);

        return view('pengajaran.santri.portofolio', compact('santri'));
    }

    public function exportPortofolioPdf(Santri $santri)
    {
        $this->authorize('view', $santri);

        $santri->load([
            'perizinans',
            'pelanggarans',
            'prestasis.pencatat',
            'riwayatPenyakits.pencatat',
            'catatanHarians.pencatat',
            'kelas'
        ]);

        $pdf = PDF::loadView('pengajaran.santri.portofolio-pdf', compact('santri'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("portofolio-{$santri->nama}-{$santri->nis}.pdf");
    }


    /**
     * TAMBAHKAN METHOD INI
     * Membuat kode registrasi unik untuk satu santri.
     */
    public function generateWaliCode(Santri $santri)
    {
        $this->authorize('update', $santri); // Hanya yang bisa update santri yang bisa generate kode

        $uniqueCode = 'WALI-' . strtoupper(Str::random(8));
        while (Santri::where('kode_registrasi_wali', $uniqueCode)->exists()) {
            $uniqueCode = 'WALI-' . strtoupper(Str::random(8));
        }

        $santri->kode_registrasi_wali = $uniqueCode;
        $santri->save();

        return redirect()->back()->with('success', 'Kode registrasi wali berhasil dibuat.');
    }

    /**
     * TAMBAHKAN METHOD INI
     * Menangani permintaan export rapor ke Excel.
     */
    public function exportRapor(Request $request, Santri $santri)
    {
        $this->authorize('view', $santri);

        $request->validate([
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|string',
        ]);

        $nilai = Nilai::where('santri_id', $santri->id)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->where('semester', $request->semester)
            ->with('mataPelajaran')
            ->get();

        if ($nilai->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data nilai untuk diexport pada periode yang dipilih.');
        }

        $fileName = 'Rapor ' . $santri->nama . ' - ' . $request->semester . ' ' . str_replace('/', '-', $request->tahun_ajaran) . '.xlsx';

        return Excel::download(new RaporSantriExport($santri, $nilai, $request->tahun_ajaran, $request->semester), $fileName);
    }

    /**
     * Menangani permintaan export rapor ke PDF.
     */
    public function exportRaporPdf(Request $request, Santri $santri)
    {
        $this->authorize('view', $santri);
        $validated = $request->validate(['tahun_ajaran' => 'required|string', 'semester' => 'required|string', 'jenis_penilaian' => 'nullable|in:nilai_tugas,nilai_uts,nilai_uas']);
        $raporData = Nilai::where('santri_id', $santri->id)->where('tahun_ajaran', $validated['tahun_ajaran'])->where('semester', $validated['semester'])->with('mataPelajaran')->get();
        if ($raporData->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data nilai untuk diexport pada periode yang dipilih.');
        }
        $jabatanWali = 'Wali Kelas ' . $santri->jenis_kelamin;
        $waliKelas = $santri->kelas->penanggungJawab()->where('tahun_ajaran', $validated['tahun_ajaran'])->whereHas('jabatan', fn($q) => $q->where('nama_jabatan', $jabatanWali))->with('user')->first();
        $namaWaliKelas = $waliKelas ? $waliKelas->user->name : '...................................';
        $pdf = PDF::loadView('santri-profile.rapor-pdf', ['santri' => $santri, 'raporData' => $raporData, 'tahunAjaran' => $validated['tahun_ajaran'], 'semester' => $validated['semester'], 'jenisPenilaian' => $validated['jenis_penilaian'] ?? null, 'namaWaliKelas' => $namaWaliKelas]);

        // [PERBAIKAN] Gunakan isset() untuk memeriksa apakah kunci ada sebelum diakses
        $jenisLaporan = isset($validated['jenis_penilaian']) ? str_replace('nilai_', '', $validated['jenis_penilaian']) : 'Rapor';
        $fileName = 'Laporan ' . ucfirst($jenisLaporan) . ' ' . $santri->nama . ' - ' . $validated['semester'] . ' ' . str_replace('/', '-', $validated['tahun_ajaran']) . '.pdf';

        return $pdf->download($fileName);
    }
}
