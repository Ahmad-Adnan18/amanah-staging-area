<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PelanggaranController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Pelanggaran::class);

        // Fitur pencarian
        $search = $request->input('search');

        $pelanggarans = Pelanggaran::with('santri.kelas')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('santri', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                })
                    ->orWhere('jenis_pelanggaran', 'like', '%' . $search . '%')
                    ->orWhere('dicatat_oleh', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(15);

        // Statistik pelanggaran
        $statistics = $this->getPelanggaranStatistics($search);

        return view('pelanggaran.index', compact('pelanggarans', 'search', 'statistics'));
    }

    public function create()
    {
        $this->authorize('create', Pelanggaran::class);
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('pelanggaran.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pelanggaran::class);
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'jenis_pelanggaran' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'keterangan' => 'nullable|string',
            'dicatat_oleh' => 'required|string|max:255',
        ]);

        Pelanggaran::create($validated);

        return redirect()->route('pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil disimpan.');
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        $this->authorize('update', $pelanggaran);
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('pelanggaran.edit', compact('pelanggaran', 'kelasList'));
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $this->authorize('update', $pelanggaran);
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'jenis_pelanggaran' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'keterangan' => 'nullable|string',
            'dicatat_oleh' => 'required|string|max:255',
        ]);

        $pelanggaran->update($validated);

        return redirect()->route('pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil diperbarui.');
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $this->authorize('delete', $pelanggaran);
        $pelanggaran->delete();
        return redirect()->route('pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $this->authorize('delete', Pelanggaran::class);
        $ids = $request->input('ids', []);
        Pelanggaran::whereIn('id', $ids)->delete();
        return redirect()->route('pelanggaran.index')->with('success', 'Catatan pelanggaran yang dipilih berhasil dihapus.');
    }

    /**
     * Get pelanggaran statistics
     */
    private function getPelanggaranStatistics($search = null)
    {
        $baseQuery = Pelanggaran::query();

        if ($search) {
            $baseQuery->whereHas('santri', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            })
                ->orWhere('jenis_pelanggaran', 'like', '%' . $search . '%')
                ->orWhere('dicatat_oleh', 'like', '%' . $search . '%');
        }

        // Total pelanggaran
        $totalPelanggaran = $baseQuery->count();

        // Santri dengan pelanggaran terbanyak (top 5)
        $topPelanggar = Santri::withCount(['pelanggarans' => function ($query) use ($search) {
            if ($search) {
                $query->whereHas('santri', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                })
                    ->orWhere('jenis_pelanggaran', 'like', '%' . $search . '%')
                    ->orWhere('dicatat_oleh', 'like', '%' . $search . '%');
            }
        }])
            ->having('pelanggarans_count', '>', 0)
            ->orderBy('pelanggarans_count', 'desc')
            ->limit(5)
            ->get();

        // Jenis pelanggaran paling umum
        $jenisPelanggaran = Pelanggaran::when($search, function ($query) use ($search) {
            $query->whereHas('santri', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            })
                ->orWhere('jenis_pelanggaran', 'like', '%' . $search . '%')
                ->orWhere('dicatat_oleh', 'like', '%' . $search . '%');
        })
            ->select('jenis_pelanggaran')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('jenis_pelanggaran')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Pelanggaran bulan ini
        $pelanggaranBulanIni = Pelanggaran::when($search, function ($query) use ($search) {
            $query->whereHas('santri', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            })
                ->orWhere('jenis_pelanggaran', 'like', '%' . $search . '%')
                ->orWhere('dicatat_oleh', 'like', '%' . $search . '%');
        })
            ->whereMonth('tanggal_kejadian', now()->month)
            ->whereYear('tanggal_kejadian', now()->year)
            ->count();

        return [
            'total_pelanggaran' => $totalPelanggaran,
            'top_pelanggar' => $topPelanggar,
            'jenis_pelanggaran' => $jenisPelanggaran,
            'pelanggaran_bulan_ini' => $pelanggaranBulanIni,
        ];
    }
}
