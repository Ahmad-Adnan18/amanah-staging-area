<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Teacher;
use App\Models\BlockedTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MataPelajaran::with('teachers');

        // Filter berdasarkan tingkatan (sekarang bisa string bebas)
        if ($request->has('tingkatan') && $request->tingkatan != '') {
            $query->where('tingkatan', 'LIKE', '%' . $request->tingkatan . '%');
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan nama pelajaran
        if ($request->has('nama_pelajaran') && $request->nama_pelajaran != '') {
            $query->where('nama_pelajaran', 'LIKE', '%' . $request->nama_pelajaran . '%');
        }

        $mataPelajarans = $query->orderBy('tingkatan')->orderBy('nama_pelajaran')->paginate(15);

        // Statistik JP per tingkatan (mengakomodasi tingkatan string)
        $jpPerTingkat = MataPelajaran::select('tingkatan', DB::raw('SUM(duration_jp) as total_jp'))
            ->groupBy('tingkatan')
            ->orderBy('tingkatan')
            ->get()
            ->pluck('total_jp', 'tingkatan');

        // Statistik JP per kategori
        $jpPerKategori = MataPelajaran::select('kategori', DB::raw('SUM(duration_jp) as total_jp'))
            ->groupBy('kategori')
            ->orderBy('kategori')
            ->get()
            ->pluck('total_jp', 'kategori');

        // Statistik blocked time
        $blockedSlotsCount = BlockedTime::count();
        $kapasitasTotal = 6 * 7; // 6 hari x 7 jam per hari
        $jamEfektif = $kapasitasTotal - $blockedSlotsCount;

        // Total JP keseluruhan
        $totalJp = MataPelajaran::sum('duration_jp');

        return view('pengajaran.mata-pelajaran.index', compact(
            'mataPelajarans', 
            'jpPerTingkat', 
            'jpPerKategori',
            'jamEfektif', 
            'totalJp',
            'request'
        ));
    }

    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $tingkatans = MataPelajaran::distinct()->pluck('tingkatan')->sort()->toArray();
        return view('pengajaran.mata-pelajaran.create', compact('teachers', 'tingkatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelajaran' => 'required|string|max:255',
            'tingkatan' => 'required|string|max:100|min:2', // UBAH: Sekarang string bebas dengan batasan panjang
            'kategori' => 'required|in:Umum,Diniyah', // UBAH: Lebih ketat, hanya dua opsi
            'duration_jp' => 'required|integer|min:1|max:10', // Tambah max untuk mencegah input tidak wajar
            'teacher_ids' => 'nullable|array|min:1', // Minimal 1 guru
            'teacher_ids.*' => 'exists:teachers,id',
            'requires_special_room' => 'nullable|boolean',
        ], [
            'tingkatan.required' => 'Tingkatan harus diisi.',
            'tingkatan.string' => 'Tingkatan harus berupa teks.',
            'tingkatan.max' => 'Tingkatan tidak boleh lebih dari 100 karakter.',
            'tingkatan.min' => 'Tingkatan minimal 2 karakter.',
            'kategori.in' => 'Kategori harus Umum atau Diniyah.',
            'duration_jp.max' => 'Durasi maksimal 10 jam pelajaran.',
            'teacher_ids.min' => 'Minimal harus pilih 1 guru pengampu.',
        ]);
        
        // Sanitasi tingkatan: trim dan capitalize
        $tingkatan = trim(ucwords(strtolower($request->tingkatan)));
        
        // Cek apakah kombinasi nama_pelajaran + tingkatan sudah ada
        $existing = MataPelajaran::where('nama_pelajaran', $request->nama_pelajaran)
                                ->where('tingkatan', $tingkatan)
                                ->first();
        
        if ($existing) {
            return back()->withErrors([
                'nama_pelajaran' => 'Kombinasi mata pelajaran dan tingkatan ini sudah ada.'
            ])->withInput();
        }

        $mataPelajaran = MataPelajaran::create([
            'nama_pelajaran' => $request->nama_pelajaran,
            'tingkatan' => $tingkatan,
            'kategori' => $request->kategori,
            'duration_jp' => $request->duration_jp,
            'requires_special_room' => $request->boolean('requires_special_room', false),
        ]);

        if ($request->has('teacher_ids') && is_array($request->teacher_ids)) {
            $mataPelajaran->teachers()->sync($request->teacher_ids);
        }

        return redirect()->route('pengajaran.mata-pelajaran.index')
                        ->with('success', 'Mata pelajaran "' . $request->nama_pelajaran . '" untuk tingkatan "' . $tingkatan . '" berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        $teachers = Teacher::orderBy('name')->get();
        $tingkatans = MataPelajaran::distinct()->pluck('tingkatan')->sort()->toArray();
        return view('pengajaran.mata-pelajaran.edit', compact('mataPelajaran', 'teachers', 'tingkatans'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $request->validate([
            'nama_pelajaran' => 'required|string|max:255',
            'tingkatan' => 'required|string|max:100|min:2', // UBAH: String bebas
            'kategori' => 'required|in:Umum,Diniyah',
            'duration_jp' => 'required|integer|min:1|max:10',
            'teacher_ids' => 'nullable|array|min:1',
            'teacher_ids.*' => 'exists:teachers,id',
            'requires_special_room' => 'nullable|boolean',
        ], [
            'tingkatan.required' => 'Tingkatan harus diisi.',
            'tingkatan.string' => 'Tingkatan harus berupa teks.',
            'tingkatan.max' => 'Tingkatan tidak boleh lebih dari 100 karakter.',
            'tingkatan.min' => 'Tingkatan minimal 2 karakter.',
            'kategori.in' => 'Kategori harus Umum atau Diniyah.',
            'duration_jp.max' => 'Durasi maksimal 10 jam pelajaran.',
            'teacher_ids.min' => 'Minimal harus pilih 1 guru pengampu.',
        ]);

        // Sanitasi tingkatan
        $tingkatan = trim(ucwords(strtolower($request->tingkatan)));
        
        // Cek duplikasi (kecuali record yang sedang diedit)
        $existing = MataPelajaran::where('nama_pelajaran', $request->nama_pelajaran)
                                ->where('tingkatan', $tingkatan)
                                ->where('id', '!=', $mataPelajaran->id)
                                ->first();
        
        if ($existing) {
            return back()->withErrors([
                'nama_pelajaran' => 'Kombinasi mata pelajaran dan tingkatan ini sudah ada untuk mata pelajaran lain.'
            ])->withInput();
        }

        $mataPelajaran->update([
            'nama_pelajaran' => $request->nama_pelajaran,
            'tingkatan' => $tingkatan,
            'kategori' => $request->kategori,
            'duration_jp' => $request->duration_jp,
            'requires_special_room' => $request->boolean('requires_special_room', false),
        ]);

        // Update relasi guru
        $mataPelajaran->teachers()->sync($request->teacher_ids ?? []);

        return redirect()->route('pengajaran.mata-pelajaran.index')
                        ->with('success', 'Mata pelajaran "' . $request->nama_pelajaran . '" untuk tingkatan "' . $tingkatan . '" berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        // Validasi: pastikan tidak ada jadwal yang menggunakan mata pelajaran ini
        // (Ini tergantung pada struktur database Anda - sesuaikan jika perlu)
        
        $namaPelajaran = $mataPelajaran->nama_pelajaran . ' (' . $mataPelajaran->tingkatan . ')';
        
        // Hapus relasi guru terlebih dahulu
        $mataPelajaran->teachers()->detach();
        
        // Hapus mata pelajaran
        $mataPelajaran->delete();

        return redirect()->route('pengajaran.mata-pelajaran.index')
                        ->with('success', 'Mata pelajaran "' . $namaPelajaran . '" berhasil dihapus.');
    }

    /**
     * Method tambahan untuk autocomplete tingkatan (opsional)
     */
    public function getTingkatanSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = MataPelajaran::select('tingkatan')
            ->where('tingkatan', 'LIKE', $query . '%')
            ->distinct()
            ->orderBy('tingkatan')
            ->limit(10)
            ->pluck('tingkatan');

        return response()->json($suggestions);
    }

    /**
     * Method untuk mendapatkan statistik mata pelajaran
     */
    public function statistics()
    {
        $stats = [
            'total_mata_pelajaran' => MataPelajaran::count(),
            'total_jp' => MataPelajaran::sum('duration_jp'),
            'per_kategori' => MataPelajaran::select('kategori', DB::raw('COUNT(*) as count, SUM(duration_jp) as total_jp'))
                ->groupBy('kategori')
                ->get(),
            'per_tingkatan' => MataPelajaran::select('tingkatan', DB::raw('COUNT(*) as count, SUM(duration_jp) as total_jp'))
                ->groupBy('tingkatan')
                ->orderBy('tingkatan')
                ->limit(10)
                ->get(),
            'needs_special_room' => [
                'yes' => MataPelajaran::where('requires_special_room', true)->count(),
                'no' => MataPelajaran::where('requires_special_room', false)->count(),
            ]
        ];

        return response()->json($stats);
    }
}