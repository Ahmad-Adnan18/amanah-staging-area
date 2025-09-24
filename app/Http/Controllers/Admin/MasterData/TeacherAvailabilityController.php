<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Teacher; // [PERUBAHAN] Menggunakan model Teacher
use App\Models\TeacherUnavailability;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TeacherAvailabilityController extends Controller
{
    /**
     * [PERUBAHAN TOTAL] Logika diubah untuk mengambil data dari model Teacher.
     */
    public function index(Request $request): View
    {
        // Memulai query dari model Teacher
        $query = Teacher::query();

        // Filter pencarian berdasarkan nama guru
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // withCount untuk efisiensi, paginate untuk membagi halaman
        $teachers = $query->withCount('unavailabilities')
                         ->orderBy('name')
                         ->paginate(15)
                         ->withQueryString();

        return view('admin.master-data.teacher-availability.index', compact('teachers'));
    }

    /**
     * [PERUBAHAN] Route Model Binding sekarang menggunakan model Teacher.
     */
    public function edit(Teacher $teacher): View
    {
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);
        
        // Logika pengambilan data ketidaktersediaan tetap sama
        $unavailableSlots = TeacherUnavailability::where('teacher_id', $teacher->id)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->day_of_week . '-' . $item->time_slot => true];
            });

        return view('admin.master-data.teacher-availability.edit', compact('teacher', 'days', 'timeSlots', 'unavailableSlots'));
    }

    /**
     * [PERUBAHAN] Route Model Binding sekarang menggunakan model Teacher.
     */
    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        // Hapus data lama
        TeacherUnavailability::where('teacher_id', $teacher->id)->delete();

        // Masukkan data baru jika ada
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
