<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SetoranTahfidz; // Kita perlukan ini untuk cek hapus data

class SuratController extends Controller
{
    /**
     * Menampilkan daftar semua surat.
     */
    public function index()
    {
        $surats = Surat::orderBy('id')->paginate(50);
        return view('admin.master-data.surats.index', compact('surats'));
    }

    /**
     * Menampilkan form untuk membuat surat baru.
     */
    public function create()
    {
        return view('admin.master-data.surats.create');
    }

    /**
     * Menyimpan surat baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|unique:surats,id',
            'nama_surat' => 'required|string|max:255',
            'jumlah_ayat' => 'required|integer|min:1',
        ]);

        Surat::create($validated);

        return redirect()->route('admin.master-data.surats.index')->with('success', 'Surat baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit surat.
     */
    public function edit(Surat $surat)
    {
        return view('admin.master-data.surats.edit', compact('surat'));
    }

    /**
     * Update data surat di database.
     */
    public function update(Request $request, Surat $surat)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', Rule::unique('surats')->ignore($surat->id)],
            'nama_surat' => 'required|string|max:255',
            'jumlah_ayat' => 'required|integer|min:1',
        ]);

        $surat->update($validated);

        return redirect()->route('admin.master-data.surats.index')->with('success', 'Data surat berhasil diperbarui.');
    }

    /**
     * Menghapus surat dari database.
     */
    public function destroy(Surat $surat)
    {
        // PENTING: Cek apakah surat ini sudah dipakai di tabel setoran
        $isUsed = SetoranTahfidz::where('surat_id', $surat->id)->exists();

        if ($isUsed) {
            return redirect()->back()->with('error', 'Gagal! Surat ini tidak bisa dihapus karena sudah digunakan dalam data setoran tahfidz.');
        }

        $surat->delete();

        return redirect()->route('admin.master-data.surats.index')->with('success', 'Surat berhasil dihapus.');
    }
}