<?php

namespace App\Http\Controllers\Perizinan;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\Santri;
use App\Http\Requests\Perizinan\StorePerizinanRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PerizinanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource (hanya yang aktif)
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Perizinan::class);

        // Search functionality
        $search = $request->get('search');

        $perizinans = Perizinan::with('santri.kelas', 'pembuat')
            ->where('status', 'aktif') // Hanya tampilkan izin aktif
            ->when($search, function ($query) use ($search) {
                $query->whereHas('santri', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nis', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        // Statistics
        $stats = $this->getStatistics();

        return view('perizinan.index', compact('perizinans', 'search', 'stats'));
    }

    /**
     * Display riwayat perizinan yang sudah selesai
     */
    public function riwayat(Request $request)
    {
        $this->authorize('viewAny', Perizinan::class);

        $search = $request->get('search');
        $filterStatus = $request->get('status', 'selesai'); // Default filter selesai

        $perizinans = Perizinan::with('santri.kelas', 'pembuat')
            ->whereIn('status', ['selesai', 'terlambat']) // Tampilkan yang selesai dan terlambat
            ->when($filterStatus && $filterStatus !== 'all', function ($query) use ($filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('santri', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nis', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        $statsRiwayat = $this->getRiwayatStatistics();

        return view('perizinan.riwayat', compact('perizinans', 'search', 'statsRiwayat', 'filterStatus'));
    }

    /**
     * Get statistics for perizinan aktif
     */
    private function getStatistics()
    {
        $today = Carbon::today();

        return [
            'total_aktif' => Perizinan::where('status', 'aktif')->count(),
            'izin_hari_ini' => Perizinan::where('status', 'aktif')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->where(function ($query) use ($today) {
                    $query->whereDate('tanggal_akhir', '>=', $today)
                        ->orWhereNull('tanggal_akhir');
                })
                ->count(),
            'akan_kembali' => Perizinan::where('status', 'aktif')
                ->whereDate('tanggal_akhir', $today)
                ->count(),
            'total_bulan_ini' => Perizinan::where('status', 'aktif')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    /**
     * Get statistics for riwayat perizinan
     */
    private function getRiwayatStatistics()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return [
            'total_selesai' => Perizinan::where('status', 'selesai')->count(),
            'total_terlambat' => Perizinan::where('status', 'terlambat')->count(),
            'selesai_bulan_ini' => Perizinan::where('status', 'selesai')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count(),
            'terlambat_bulan_ini' => Perizinan::where('status', 'terlambat')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Santri $santri)
    {
        $this->authorize('create', Perizinan::class);
        return view('perizinan.create', compact('santri'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerizinanRequest $request)
    {
        $this->authorize('create', Perizinan::class);

        $validated = $request->validated();

        // Tambahkan ID user yang sedang login sebagai pembuat izin
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'aktif';

        Perizinan::create($validated);

        return redirect()->route('dashboard')->with('success', 'Data perizinan berhasil disimpan.');
    }

    /**
     * Mengubah status perizinan menjadi selesai
     */
    public function destroy(Perizinan $perizinan)
    {
        $this->authorize('delete', $perizinan);

        // Cek apakah santri sudah kembali tepat waktu
        $today = Carbon::today();
        $status = 'selesai';

        if ($perizinan->tanggal_akhir && $today->greaterThan($perizinan->tanggal_akhir)) {
            $status = 'terlambat'; // Status terlambat jika melebihi tanggal akhir
        }

        $perizinan->update([
            'status' => $status,
            'updated_by' => Auth::id()
        ]);

        $message = $status === 'terlambat'
            ? 'Perizinan telah diselesaikan dengan status TERLAMBAT.'
            : 'Perizinan telah diselesaikan.';

        return redirect()->route('perizinan.index')->with('success', $message);
    }

    /**
     * Mengubah status beberapa perizinan sekaligus menjadi selesai
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:perizinans,id',
        ]);

        $perizinans = Perizinan::whereIn('id', $request->ids)->get();
        $today = Carbon::today();

        foreach ($perizinans as $perizinan) {
            $this->authorize('delete', $perizinan);

            // Tentukan status berdasarkan tanggal kembali
            $status = 'selesai';
            if ($perizinan->tanggal_akhir && $today->greaterThan($perizinan->tanggal_akhir)) {
                $status = 'terlambat';
            }

            $perizinan->update([
                'status' => $status,
                'updated_by' => Auth::id()
            ]);
        }

        return redirect()->route('perizinan.index')->with('success', 'Perizinan yang dipilih telah diselesaikan.');
    }

    /**
     * Generate PDF surat izin.
     */
    public function generatePdf(Perizinan $perizinan)
    {
        $this->authorize('view', $perizinan);

        $perizinan->load('santri', 'pembuat');

        $pdf = Pdf::loadView('perizinan.pdf', compact('perizinan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('surat_izin_' . $perizinan->santri->nis . '_' . $perizinan->id . '.pdf');
    }
}
