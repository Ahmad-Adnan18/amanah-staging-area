<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Santri;
use App\Models\Jabatan;
use App\Models\JabatanUser;
use App\Models\Room;
use App\Models\MataPelajaran;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WaliCodesExport;

class KelasController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Kelas::class);
        
        // [PENYESUAIAN] Eager load relasi penanggungJawab beserta user dan jabatannya
        $kelas_list = Kelas::withCount('santris')
            ->with(['room', 'penanggungJawab.user', 'penanggungJawab.jabatan'])
            ->latest()
            ->paginate(50);
        
        $hasilPencarianSantri = collect();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $hasilPencarianSantri = Santri::with('kelas')
                ->where('nama', 'like', "%{$searchTerm}%")
                ->orWhere('nis', 'like', "%{$searchTerm}%")
                ->get();
        }
        
        return view('pengajaran.kelas.index', compact('kelas_list', 'hasilPencarianSantri'));
    }

    public function create()
    {
        $this->authorize('create', Kelas::class);
        
        $rooms = Room::orderBy('name')->get();
        // [PENYESUAIAN] Mengambil tingkatan dinamis dari mata pelajaran
        $tingkatans = MataPelajaran::select('tingkatan')->distinct()->orderBy('tingkatan')->pluck('tingkatan');

        return view('pengajaran.kelas.create', compact('rooms', 'tingkatans'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Kelas::class);

        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'tingkatan' => 'required|string|max:255',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        Kelas::create($validatedData);

        return redirect()->route('pengajaran.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $this->authorize('update', $kela);

        // Data untuk form jabatan
        $users = User::whereIn('role', ['pengajaran', 'pengasuhan', 'kesehatan', 'ustadz_umum', 'admin'])->orderBy('name')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $penanggungJawab = $kela->penanggungJawab()->with('user', 'jabatan')->get();

        // Data untuk form detail kelas
        $rooms = Room::orderBy('name')->get();
        $tingkatans = MataPelajaran::select('tingkatan')->distinct()->orderBy('tingkatan')->pluck('tingkatan');

        // Data untuk Alokasi Guru Mengajar
        $allMataPelajarans = MataPelajaran::where('tingkatan', $kela->tingkatan)
            ->with('teachers') // Eager load kandidat guru untuk setiap mapel
            ->orderBy('nama_pelajaran')
            ->get();
            
        $assignedSubjects = $kela->mataPelajarans->pluck('pivot.teacher_id', 'id');

        return view('pengajaran.kelas.edit', compact('kela', 'users', 'jabatans', 'penanggungJawab', 'rooms', 'tingkatans', 'allMataPelajarans', 'assignedSubjects'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $this->authorize('update', $kela);

        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kela->id,
            'tingkatan' => 'required|string|max:255',
            'room_id' => 'nullable|exists:rooms,id',
            'is_active_for_scheduling' => 'required|boolean',
        ]);

        $kela->update($validatedData);

        return redirect()->route('pengajaran.kelas.edit', $kela)->with('success', 'Detail kelas berhasil diperbarui.');
    }

    public function assignSubjects(Request $request, Kelas $kela)
    {
        $this->authorize('update', $kela);

        $syncData = [];
        if ($request->has('subjects')) {
            foreach ($request->subjects as $mapelId => $teacherId) {
                if (!empty($teacherId)) {
                    $syncData[$mapelId] = ['teacher_id' => $teacherId];
                }
            }
        }

        $kela->mataPelajarans()->sync($syncData);

        return redirect()->back()->with('success', 'Alokasi guru mengajar berhasil disimpan.');
    }

    public function assignJabatan(Request $request, Kelas $kelas)
    {
        $this->authorize('update', $kelas);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        JabatanUser::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'kelas_id' => $kelas->id,
                'jabatan_id' => $request->jabatan_id,
            ],
            [
                'tahun_ajaran' => $request->tahun_ajaran,
            ]
        );

        return redirect()->back()->with('success', 'Penanggung jawab berhasil ditambahkan.');
    }

    public function removeJabatan(JabatanUser $jabatanUser)
    {
        $this->authorize('update', $jabatanUser->kelas);
        $jabatanUser->delete();
        return redirect()->back()->with('success', 'Penanggung jawab berhasil dihapus.');
    }

    public function destroy(Kelas $kela)
    {
        $this->authorize('delete', $kela);
        $kela->delete();
        return redirect()->route('pengajaran.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    // ... sisa method lainnya tidak berubah ...
    public function getSantrisJson(Kelas $kelas)
    {
        $santris = $kelas->santris()->select('id', 'nama')->orderBy('nama')->get();
        return response()->json($santris);
    }

    public function generateAllWaliCodes()
    {
        $this->authorize('create', Kelas::class);
        Artisan::call('app:generate-wali-codes');
        return redirect()->back()->with('success', 'Proses pembuatan kode registrasi untuk semua santri telah selesai.');
    }

    public function exportWaliCodes()
    {
        $this->authorize('viewAny', Kelas::class);
        return Excel::download(new WaliCodesExport, 'daftar-kode-registrasi-wali.xlsx');
    }
}

