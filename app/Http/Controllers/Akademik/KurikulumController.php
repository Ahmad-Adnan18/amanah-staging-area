<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KurikulumTemplate;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

/**
 * KurikulumController sekarang hanya bertanggung jawab untuk:
 * 1. Mengelola template kurikulum (paket mata pelajaran).
 * 2. Menerapkan template tersebut ke satu atau lebih kelas.
 *
 * Logika untuk alokasi guru spesifik per mata pelajaran telah dipindahkan
 * sepenuhnya ke KelasController.
 */
class KurikulumController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen kurikulum.
     */
    public function index()
    {
        $templates = KurikulumTemplate::withCount('mataPelajarans')->orderBy('nama_template')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('akademik.kurikulum.index', compact('templates', 'kelasList'));
    }

    /**
     * Menyimpan template kurikulum baru.
     */
    public function storeTemplate(Request $request)
    {
        $request->validate(['nama_template' => 'required|string|max:255|unique:kurikulum_templates']);
        KurikulumTemplate::create($request->all());
        return back()->with('success', 'Template kurikulum berhasil dibuat.');
    }

    /**
     * [PENYESUAIAN LENGKAP]
     * Menyiapkan semua data yang dibutuhkan untuk view edit-template yang interaktif.
     */
    public function editTemplate(KurikulumTemplate $template)
    {
        $allMataPelajaran = MataPelajaran::orderBy('tingkatan')
                                      ->orderBy('nama_pelajaran')
                                      ->get();
        
        // Mengambil daftar tingkatan unik yang ada di database untuk filter dinamis
        $tingkatans = $allMataPelajaran->pluck('tingkatan')->unique()->sort();

        // Mengelompokkan mapel berdasarkan tingkatan untuk ditampilkan di view
        $groupedMapel = $allMataPelajaran->groupBy('tingkatan');

        // Mengambil ID mapel yang sudah terpasang di template ini
        $assignedMapelIds = $template->mataPelajarans->pluck('id')->toArray();
        
        // Mengirim semua data yang dibutuhkan oleh view
        return view('akademik.kurikulum.edit-template', compact(
            'template', 
            'allMataPelajaran', 
            'assignedMapelIds',
            'groupedMapel', // Data untuk Alpine.js
            'tingkatans'    // Data untuk filter dinamis
        ));
    }

    /**
     * Mengupdate daftar mata pelajaran dalam sebuah template.
     */
    public function updateTemplate(Request $request, KurikulumTemplate $template)
    {
        $request->validate(['mata_pelajaran_ids' => 'nullable|array']);
        $template->mataPelajarans()->sync($request->mata_pelajaran_ids ?? []);
        return redirect()->route('akademik.kurikulum.index')->with('success', 'Template kurikulum berhasil diperbarui.');
    }

    /**
     * Menghapus template kurikulum.
     */
    public function destroyTemplate(KurikulumTemplate $template)
    {
        $template->delete();
        return back()->with('success', 'Template kurikulum berhasil dihapus.');
    }

    /**
     * Menerapkan sebuah template (paket mata pelajaran) ke kelas yang dipilih.
     */
    public function applyTemplate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:kurikulum_templates,id',
            'kelas_ids' => 'required|array|min:1',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $template = KurikulumTemplate::findOrFail($request->template_id);
        $mapelIds = $template->mataPelajarans->pluck('id');

        foreach ($request->kelas_ids as $kelasId) {
            $kelas = Kelas::find($kelasId);
            $kelas->kurikulumTemplate()->associate($template);
            $kelas->save();
            $kelas->mataPelajarans()->syncWithoutDetaching($mapelIds);
        }

        return back()->with('success', 'Template kurikulum berhasil diterapkan ke kelas yang dipilih.');
    }
}

