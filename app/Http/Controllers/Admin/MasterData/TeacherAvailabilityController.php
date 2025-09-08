<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\TeacherUnavailability; // Pastikan model ini di-import

class TeacherAvailabilityController extends Controller
{
    /**
     * [PERBAIKAN UTAMA] Method index diubah total untuk mendukung:
     * 1. Pencarian (Search)
     * 2. Paginasi (data dibagi per halaman)
     * 3. Penghitungan efisien dengan withCount()
     */
    public function index(Request $request): View
    {
        // Memulai query untuk User yang bukan wali santri
        $query = User::query()->where('role', '!=', 'wali_santri');

        // Jika ada input pencarian, filter berdasarkan nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Ini adalah baris kunci:
        // - withCount('unavailabilities'): Secara efisien menghitung jumlah relasi 'unavailabilities'
        //   dan menyimpannya dalam properti baru bernama 'unavailabilities_count'.
        // - orderBy('name'): Mengurutkan berdasarkan nama.
        // - paginate(15): Membagi hasil menjadi halaman-halaman (15 data per halaman).
        $teachers = $query->withCount('unavailabilities')
                         ->orderBy('name')
                         ->paginate(15)
                         ->withQueryString(); // Agar filter pencarian tidak hilang saat pindah halaman

        // Kirim data 'teachers' yang sudah berisi 'unavailabilities_count' ke view
        return view('admin.master-data.teacher-availability.index', compact('teachers'));
    }

    public function edit(User $teacher): View
    {
        if ($teacher->role === 'wali_santri') {
            abort(404);
        }

        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);
        
        $unavailableSlots = TeacherUnavailability::where('teacher_id', $teacher->id)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->day_of_week . '-' . $item->time_slot => true];
            });

        return view('admin.master-data.teacher-availability.edit', compact('teacher', 'days', 'timeSlots', 'unavailableSlots'));
    }

    public function update(Request $request, User $teacher): RedirectResponse
    {
        TeacherUnavailability::where('teacher_id', $teacher->id)->delete();

        if ($request->has('unavailable_slots')) {
            $slotsToInsert = [];
            foreach ($request->unavailable_slots as $slot) {
                list($day, $time) = explode('-', $slot);
                $slotsToInsert[] = [
                    'teacher_id' => $teacher->id,
                    'day_of_week' => $day,
                    'time_slot' => $time,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            TeacherUnavailability::insert($slotsToInsert);
        }

        return redirect()->route('admin.teacher-availability.index')
            ->with('success', 'Ketersediaan untuk ' . $teacher->name . ' berhasil diperbarui.');
    }
}
