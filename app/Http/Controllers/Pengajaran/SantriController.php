<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Santri;
use App\Http\Requests\Pengajaran\StoreSantriRequest;
use App\Http\Requests\Pengajaran\UpdateSantriRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, Kelas $kelas = null)
    {
        $this->authorize('viewAny', Santri::class);

        $query = $kelas ? $kelas->santris() : Santri::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhere('nis', 'like', "%{$searchTerm}%");
            });
        }

        $santris = $query->latest()->paginate(10)->withQueryString();

        return view('pengajaran.santri.index', compact('santris', 'kelas'));
    }

    public function create(Kelas $kelas)
    {
        $this->authorize('create', Santri::class);
        return view('pengajaran.santri.create', compact('kelas'));
    }

    public function store(StoreSantriRequest $request)
    {
        $this->authorize('create', Santri::class);
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_santri', 'public');
            $validated['foto'] = $path;
        }

        if (!Kelas::find($validated['kelas_id'])) {
            return back()->withErrors(['kelas_id' => 'Kelas tidak ditemukan.']);
        }

        $santri = Santri::create($validated);

        return redirect()->route('pengajaran.santris.index', ['kelas' => $santri->kelas_id ?? 0])
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function edit(Santri $santri)
    {
        $this->authorize('update', $santri);
        return view('pengajaran.santri.edit', compact('santri'));
    }

    public function update(UpdateSantriRequest $request, Santri $santri)
    {
        $this->authorize('update', $santri);
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            if ($santri->foto) {
                Storage::disk('public')->delete($santri->foto);
            }
            $path = $request->file('foto')->store('foto_santri', 'public');
            $validated['foto'] = $path;
        }

        if (!Kelas::find($validated['kelas_id'])) {
            return back()->withErrors(['kelas_id' => 'Kelas tidak ditemukan.']);
        }

        $santri->update($validated);

        return redirect()->route('pengajaran.santris.index', ['kelas' => $santri->kelas_id ?? 0])
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        $this->authorize('delete', $santri);
        $kelas_id = $santri->kelas_id;

        if ($santri->foto) {
            Storage::disk('public')->delete($santri->foto);
        }

        $santri->delete();

        return redirect()->route('pengajaran.santris.index', ['kelas' => $kelas_id ?? 0])
            ->with('success', 'Data santri berhasil dihapus.');
    }
}
