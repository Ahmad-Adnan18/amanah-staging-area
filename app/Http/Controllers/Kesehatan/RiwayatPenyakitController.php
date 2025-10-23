<?php

namespace App\Http\Controllers\Kesehatan;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\RiwayatPenyakit;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RiwayatPenyakitController extends Controller
{
    use AuthorizesRequests;

    public function create(Santri $santri)
    {
        // PERBAIKAN: Gunakan array dengan model Santri
        $this->authorize('create', [RiwayatPenyakit::class, $santri]);
        return view('kesehatan.riwayat-penyakit.create', compact('santri'));
    }

    public function store(Request $request, Santri $santri)
    {
        // PERBAIKAN: Gunakan array dengan model Santri
        $this->authorize('create', [RiwayatPenyakit::class, $santri]);

        $validated = $request->validate([
            'nama_penyakit' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_diagnosis' => 'required|date',
            'status' => 'required|in:aktif,sembuh,kronis',
            'penanganan' => 'nullable|string',
        ]);

        $validated['santri_id'] = $santri->id;
        $validated['dicatat_oleh'] = auth()->user()->id;

        RiwayatPenyakit::create($validated);

        return redirect()->route('santri.profil.show', $santri)
            ->with('success', 'Riwayat penyakit berhasil ditambahkan.');
    }

    public function edit(RiwayatPenyakit $riwayatPenyakit)
    {
        $this->authorize('update', $riwayatPenyakit);
        return view('kesehatan.riwayat-penyakit.edit', compact('riwayatPenyakit'));
    }

    public function update(Request $request, RiwayatPenyakit $riwayatPenyakit)
    {
        $this->authorize('update', $riwayatPenyakit);

        $validated = $request->validate([
            'nama_penyakit' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_diagnosis' => 'required|date',
            'status' => 'required|in:aktif,sembuh,kronis',
            'penanganan' => 'nullable|string',
        ]);

        $riwayatPenyakit->update($validated);

        return redirect()->route('santri.profil.show', $riwayatPenyakit->santri)
            ->with('success', 'Riwayat penyakit berhasil diperbarui.');
    }

    public function destroy(RiwayatPenyakit $riwayatPenyakit)
    {
        $this->authorize('delete', $riwayatPenyakit);
        $santri = $riwayatPenyakit->santri;
        $riwayatPenyakit->delete();

        return redirect()->route('santri.profil.show', $santri)
            ->with('success', 'Riwayat penyakit berhasil dihapus.');
    }
}
