<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Teacher; // Pastikan model Teacher sudah dibuat
use App\Imports\TeachersImport; // Pastikan kelas import sudah dibuat
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    /**
     * Menampilkan daftar semua guru.
     */
    public function index()
    {
        // Mengambil semua data guru, diurutkan berdasarkan nama
        $teachers = Teacher::orderBy('name')->paginate(50);
        return view('admin.master-data.teachers.index', compact('teachers'));
    }

    /**
     * Menampilkan form untuk membuat guru baru.
     */
    public function create()
    {
        return view('admin.master-data.teachers.create');
    }

    /**
     * Menyimpan data guru baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_code' => 'nullable|string|max:10|unique:teachers,teacher_code',
        ]);

        Teacher::create($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data guru.
     */
    public function edit(Teacher $teacher)
    {
        return view('admin.master-data.teachers.edit', compact('teacher'));
    }

    /**
     * Mengupdate data guru di database.
     */
    public function update(Request $request, Teacher $teacher)
    {
        // Validasi input, pastikan 'unique' mengabaikan data saat ini
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_code' => 'nullable|string|max:10|unique:teachers,teacher_code,' . $teacher->id,
        ]);

        $teacher->update($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Menghapus data guru dari database.
     */
    public function destroy(Teacher $teacher)
    {
        // Tambahkan pengecekan jika guru masih terikat jadwal (opsional tapi disarankan)
        // if ($teacher->schedules()->exists()) {
        //     return redirect()->back()->with('error', 'Guru tidak bisa dihapus karena masih memiliki jadwal mengajar.');
        // }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }

    /**
     * Mengimpor data guru dari file Excel.
     */
    public function import(Request $request)
    {
        // 1. Validasi file yang di-upload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // Hanya izinkan file excel
        ]);

        try {
            // 2. Proses impor menggunakan Maatwebsite/Excel
            Excel::import(new TeachersImport, $request->file('file'));
            
            // 3. Kembalikan dengan pesan sukses
            return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diimpor dari Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Tangkap error validasi dari dalam file Excel
            $failures = $e->failures();
            // Anda bisa format pesan error di sini untuk ditampilkan ke user
            return redirect()->route('admin.teachers.index')->with('error', 'Terjadi error saat validasi data Excel.');
        } catch (\Exception $e) {
            // Tangkap error umum lainnya
            return redirect()->route('admin.teachers.index')->with('error', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
    }
}