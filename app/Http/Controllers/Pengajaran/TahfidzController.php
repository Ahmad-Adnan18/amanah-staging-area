<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\SetoranTahfidz;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TahfidzController extends Controller
{
    // 1. Halaman Input Setoran (Form + Log Harian)
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $setorans = collect();

        $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'tanggal' => 'nullable|date',
        ]);

        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        if ($request->filled('kelas_id')) {
            $setorans = SetoranTahfidz::where('kelas_id', $request->kelas_id)
                ->where('tanggal_setor', $tanggal)
                ->with('santri', 'teacher', 'surat')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('ubudiyah.index', compact('kelasList', 'setorans', 'tanggal'));
    }

    // 2. Logika Simpan Setoran Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_setor' => 'required|date',
            'jenis_setoran' => ['required', Rule::in(['baru', 'murojaah'])],
            'surat_id' => 'required|exists:surats,id',
            'ayat_mulai' => 'required|integer|min:1',
            'ayat_selesai' => 'required|integer|min:1|gte:ayat_mulai',
            'nilai' => ['required', Rule::in(['mumtaz', 'jayyid_jiddan', 'jayyid', 'maqbul'])],
            'penerima_manual' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        SetoranTahfidz::create([
            ...$validated,
            'teacher_id' => Auth::user()->teacher?->id, // Asumsi ustadz login punya relasi teacher
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Setoran berhasil disimpan.');
    }

    // 3. Hapus Setoran (jika ada salah input)
    public function destroy(SetoranTahfidz $setoran)
    {
        // Tambahkan otorisasi jika perlu
        $setoran->delete();
        return response()->json(['message' => 'Setoran berhasil dihapus.']);
    }

    // 4. Halaman Laporan Mutaba'ah (Progress Santri)
    public function mutabaah(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $santri = null;
        $setorans = collect();

        $request->validate([
            'santri_id' => 'nullable|exists:santris,id',
        ]);

        if ($request->filled('santri_id')) {
            $santri = Santri::with('kelas')->findOrFail($request->santri_id);
            $setorans = SetoranTahfidz::where('santri_id', $santri->id)
                ->with('surat', 'teacher')
                ->orderBy('tanggal_setor', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('ubudiyah.mutabaah', compact('kelasList', 'santri', 'setorans'));
    }

    // --- API Helpers ---

    // 5. API untuk ambil daftar santri
    public function getSantriByKelas($kelasId)
    {
        $santris = Santri::where('kelas_id', $kelasId)->orderBy('nama')->get(['id', 'nama']);
        return response()->json($santris);
    }

    // 6. API untuk ambil daftar surat
    public function getSuratList()
    {
        $surats = Surat::orderBy('id')->get(['id', 'nama_surat', 'jumlah_ayat']);
        return response()->json($surats);
    }
}