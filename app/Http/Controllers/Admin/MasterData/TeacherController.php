<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Imports\TeachersImport;
use Illuminate\Http\Request; // [MODIFIKASI] Tambahkan Request
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    /**
     * Menampilkan daftar semua guru dengan fitur pencarian.
     */
    public function index(Request $request) // [MODIFIKASI] Tambahkan Request
    {
        // Ambil jumlah total guru untuk ditampilkan di statistik
        $totalTeachers = Teacher::count();

        // Query dasar untuk mengambil data guru
        $query = Teacher::query();

        // [LOGIKA BARU] Jika ada input pencarian, filter data guru
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('teacher_code', 'like', "%{$search}%");
            });
        }

        // Ambil data guru yang sudah difilter, urutkan, dan paginasi
        $teachers = $query->orderBy('name')->paginate(50)->withQueryString();

        return view('admin.master-data.teachers.index', compact('teachers', 'totalTeachers'));
    }

    /**
     * [PENYESUAIAN] Menampilkan form untuk membuat guru baru dan mengirimkan
     * daftar user yang bisa dihubungkan.
     */
    public function create()
    {
        // Mengambil semua user yang bukan wali santri DAN belum terhubung dengan guru lain.
        $users = User::where('role', '!=', 'wali_santri')
                     ->whereDoesntHave('teacher')
                     ->orderBy('name')
                     ->get();

        return view('admin.master-data.teachers.create', compact('users'));
    }

    /**
     * [PENYESUAIAN] Menyimpan data guru baru ke database, termasuk user_id jika ada.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_code' => 'nullable|string|max:10|unique:teachers,teacher_code',
            'user_id' => 'nullable|exists:users,id|unique:teachers,user_id' // Validasi untuk user_id
        ]);

        Teacher::create($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * [PENYESUAIAN] Menampilkan form untuk mengedit data guru dan mengirimkan
     * daftar user yang bisa dihubungkan.
     */
    public function edit(Teacher $teacher)
    {
        // Ambil semua user yang bukan wali santri DAN belum terhubung dengan guru lain,
        // ATAU user yang saat ini sudah terhubung dengan guru yang diedit.
        $users = User::where('role', '!=', 'wali_santri')
                     ->whereDoesntHave('teacher')
                     ->orWhere('id', $teacher->user_id)
                     ->orderBy('name')
                     ->get();
                     
        return view('admin.master-data.teachers.edit', compact('teacher', 'users'));
    }

    /**
     * [PENYESUAIAN] Mengupdate data guru di database, termasuk user_id jika ada.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_code' => 'nullable|string|max:10|unique:teachers,teacher_code,' . $teacher->id,
            // Validasi user_id, pastikan unik kecuali untuk user_id yang saat ini sudah terpasang
            'user_id' => 'nullable|exists:users,id|unique:teachers,user_id,' . $teacher->id . ',id,user_id,' . ($request->user_id ?? 'NULL')
        ]);

        $teacher->update($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Menghapus data guru dari database.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }

    /**
     * Mengimpor data guru dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls|max:2048']);
        
        try {
            Excel::import(new TeachersImport, $request->file('file'));
            return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diimpor dari Excel.');
        } catch (\Exception $e) {
            return redirect()->route('admin.teachers.index')->with('error', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
    }
}

