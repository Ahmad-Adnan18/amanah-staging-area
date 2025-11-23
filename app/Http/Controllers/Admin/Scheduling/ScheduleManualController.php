<?php
// File: app/Http/Controllers/Admin/Scheduling/ScheduleManualController.php

namespace App\Http\Controllers\Admin\Scheduling;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Teacher;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\TeacherUnavailability;
use App\Models\BlockedTime;
use App\Models\HourPriority;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ScheduleManualController extends Controller
{
    public function create()
    {
        $classes = Kelas::with('room')->where('is_active_for_scheduling', true)->get();
        $subjects = MataPelajaran::all();
        $teachers = Teacher::all();
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);

        return view('admin.scheduling.manual.create', compact(
            'classes',
            'subjects',
            'teachers',
            'days',
            'timeSlots'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|integer|min:1|max:6',
            'time_slot' => 'required|integer|min:1|max:7',
        ]);

        // Ambil room_id dari kelas
        $kelas = Kelas::find($validated['kelas_id']);
        $validated['room_id'] = $kelas->room_id;

        $conflicts = $this->checkConflicts($validated);

        if (!empty($conflicts)) {
            return back()->withErrors($conflicts)->withInput();
        }

        Schedule::create($validated);

        return redirect()->route('admin.schedule.view.grid')
            ->with('success', 'Jadwal berhasil ditambahkan secara manual.');
    }

    public function edit(Schedule $schedule)
    {
        $classes = Kelas::with('room')->where('is_active_for_scheduling', true)->get();
        $subjects = MataPelajaran::all();
        $teachers = Teacher::all();
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);

        return view('admin.scheduling.manual.edit', compact(
            'schedule',
            'classes',
            'subjects',
            'teachers',
            'days',
            'timeSlots'
        ));
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|integer|min:1|max:6',
            'time_slot' => 'required|integer|min:1|max:7',
        ]);

        // Ambil room_id dari kelas
        $kelas = Kelas::find($validated['kelas_id']);
        $validated['room_id'] = $kelas->room_id;

        $conflicts = $this->checkUpdateConflicts($validated, $schedule->id);

        if (!empty($conflicts)) {
            return back()->withErrors($conflicts)->withInput();
        }

        $schedule->update($validated);

        return redirect()->route('admin.schedule.view.grid')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedule.view.grid')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    // Quick Actions untuk Grid View
    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|integer|min:1|max:6',
            'time_slot' => 'required|integer|min:1|max:7',
        ]);

        // Ambil room_id dari kelas
        $kelas = Kelas::find($validated['kelas_id']);
        $validated['room_id'] = $kelas->room_id;

        $conflicts = $this->checkConflicts($validated);

        if (!empty($conflicts)) {
            return response()->json(['success' => false, 'errors' => $conflicts], 422);
        }

        Schedule::create($validated);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan']);
    }

    public function quickDelete(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus']);
    }

    private function checkConflicts(array $data): array
    {
        $errors = [];

        // Cek konflik kelas
        if (Schedule::where('kelas_id', $data['kelas_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->exists()
        ) {
            $errors[] = 'Kelas sudah memiliki jadwal di slot waktu ini.';
        }

        // Cek konflik guru
        if (Schedule::where('teacher_id', $data['teacher_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->exists()
        ) {
            $errors[] = 'Guru sudah memiliki jadwal di slot waktu ini.';
        }

        // Cek konflik ruangan
        if (Schedule::where('room_id', $data['room_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->exists()
        ) {
            $errors[] = 'Ruangan sudah dipakai di slot waktu ini.';
        }

        // Cek ketidaktersediaan guru
        if (TeacherUnavailability::where('teacher_id', $data['teacher_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->exists()
        ) {
            $errors[] = 'Guru tidak tersedia di slot waktu ini.';
        }

        // Cek waktu yang diblokir
        if (BlockedTime::where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->exists()
        ) {
            $errors[] = 'Slot waktu ini diblokir untuk penjadwalan.';
        }

        // Cek prioritas jam untuk mata pelajaran
        $subject = MataPelajaran::find($data['mata_pelajaran_id']);
        if ($subject) {
            $priority = HourPriority::where('subject_category', $subject->kategori)
                ->where('day_of_week', $data['day_of_week'])
                ->where('time_slot', $data['time_slot'])
                ->first();

            if ($priority && !$priority->is_allowed) {
                $errors[] = 'Mata pelajaran ini tidak diizinkan di slot waktu ini.';
            }
        }

        return $errors;
    }

    private function checkUpdateConflicts(array $data, int $scheduleId): array
    {
        $errors = [];

        // Sama seperti checkConflicts tapi exclude schedule yang sedang diedit
        if (Schedule::where('kelas_id', $data['kelas_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->where('id', '!=', $scheduleId)
            ->exists()
        ) {
            $errors[] = 'Kelas sudah memiliki jadwal di slot waktu ini.';
        }

        if (Schedule::where('teacher_id', $data['teacher_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->where('id', '!=', $scheduleId)
            ->exists()
        ) {
            $errors[] = 'Guru sudah memiliki jadwal di slot waktu ini.';
        }

        if (Schedule::where('room_id', $data['room_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->where('id', '!=', $scheduleId)
            ->exists()
        ) {
            $errors[] = 'Ruangan sudah dipakai di slot waktu ini.';
        }

        // Validasi lainnya sama...
        if (TeacherUnavailability::where('teacher_id', $data['teacher_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->exists()
        ) {
            $errors[] = 'Guru tidak tersedia di slot waktu ini.';
        }

        if (BlockedTime::where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->exists()
        ) {
            $errors[] = 'Slot waktu ini diblokir untuk penjadwalan.';
        }

        return $errors;
    }

    public function grid()
    {
        $classes = Kelas::with('room')->where('is_active_for_scheduling', true)->get();
        $subjects = MataPelajaran::all();
        $teachers = Teacher::all();
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];

        return view('admin.scheduling.manual.grid', compact(
            'classes',
            'subjects',
            'teachers',
            'days'
        ));
    }

    // Method baru untuk grid data
    public function gridData()
    {
        $classes = Kelas::with('room')->where('is_active_for_scheduling', true)->orderBy('nama_kelas')->get();
        
        // [PERBAIKAN] Hanya ambil mata pelajaran unik berdasarkan kombinasi nama dan tingkatan
        $subjects = MataPelajaran::select('id', 'nama_pelajaran', 'tingkatan')
            ->distinct()
            ->orderBy('tingkatan')
            ->orderBy('nama_pelajaran')
            ->get();
            
        $teachers = Teacher::select('id', 'name', 'teacher_code')->orderBy('name')->get();
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];

        // FIX: Load dengan EAGER LOADING relationships
        $schedules = Schedule::with([
            'subject:id,nama_pelajaran',
            'teacher:id,name',
            'room:id,name',
            'kelas:id,nama_kelas,room_id'
        ])
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'kelas_id' => $schedule->kelas_id,
                    'mata_pelajaran_id' => $schedule->mata_pelajaran_id,
                    'teacher_id' => $schedule->teacher_id,
                    'room_id' => $schedule->room_id,
                    'day_of_week' => $schedule->day_of_week,
                    'time_slot' => $schedule->time_slot,

                    // Data dari relationship - PASTIKAN NAMA FIELDNYA BENAR
                    'subject_name' => $schedule->subject ? $schedule->subject->nama_pelajaran : 'N/A',
                    'teacher_name' => $schedule->teacher ? $schedule->teacher->name : 'N/A',
                    'room_name' => $schedule->room ? $schedule->room->name : 'N/A',
                    'kelas_name' => $schedule->kelas ? $schedule->kelas->nama_kelas : 'N/A',
                    'kelas_room_id' => $schedule->kelas ? $schedule->kelas->room_id : null,

                    'has_conflict' => $this->checkSingleConflict($schedule)
                ];
            });

        return response()->json([
            'classes' => $classes,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'days' => $days,
            'schedules' => $schedules
        ]);
    }

    // Method untuk quick update
    public function quickUpdate(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        // Ambil room_id dari kelas
        $kelas = Kelas::find($schedule->kelas_id);
        $validated['room_id'] = $kelas->room_id;

        $conflicts = $this->checkUpdateConflicts(array_merge($validated, [
            'kelas_id' => $schedule->kelas_id,
            'day_of_week' => $schedule->day_of_week,
            'time_slot' => $schedule->time_slot
        ]), $schedule->id);

        if (!empty($conflicts)) {
            return response()->json(['success' => false, 'errors' => $conflicts], 422);
        }

        $schedule->update($validated);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui']);
    }

    // Method untuk bulk update
    public function bulkUpdate(Request $request)
    {
        $updates = $request->input('updates', []);
        $successCount = 0;
        $errors = [];

        foreach ($updates as $update) {
            // Ambil room_id dari kelas
            $kelas = Kelas::find($update['kelas_id']);
            $update['room_id'] = $kelas->room_id;

            $conflicts = $this->checkConflicts($update);

            if (empty($conflicts)) {
                Schedule::updateOrCreate(
                    [
                        'kelas_id' => $update['kelas_id'],
                        'day_of_week' => $update['day_of_week'],
                        'time_slot' => $update['time_slot']
                    ],
                    $update
                );
                $successCount++;
            } else {
                $errors[] = "Konflik di kelas {$update['kelas_id']}, hari {$update['day_of_week']}, jam {$update['time_slot']}: " . implode(', ', $conflicts);
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'updated' => $successCount,
                'errors' => $errors
            ], 422);
        }

        return response()->json([
            'success' => true,
            'updated' => $successCount,
            'message' => "Berhasil update {$successCount} jadwal"
        ]);
    }

    // Method untuk copy pattern
    public function copyPattern(Request $request)
    {
        $validated = $request->validate([
            'source_class_id' => 'required|exists:kelas,id',
            'target_class_id' => 'required|exists:kelas,id',
        ]);

        $sourceSchedules = Schedule::where('kelas_id', $validated['source_class_id'])->get();
        $copiedCount = 0;

        foreach ($sourceSchedules as $sourceSchedule) {
            $newSchedule = $sourceSchedule->replicate();
            $newSchedule->kelas_id = $validated['target_class_id'];

            $conflicts = $this->checkConflicts($newSchedule->toArray());

            if (empty($conflicts)) {
                $newSchedule->save();
                $copiedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'copied' => $copiedCount,
            'message' => "Berhasil copy {$copiedCount} jadwal dari kelas sumber"
        ]);
    }

    // Helper method untuk check conflict single schedule
    private function checkSingleConflict(Schedule $schedule): bool
    {
        $data = $schedule->toArray();

        // Cek konflik guru
        $teacherConflict = Schedule::where('teacher_id', $data['teacher_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->where('id', '!=', $schedule->id)
            ->exists();

        // Cek konflik ruangan
        $roomConflict = Schedule::where('room_id', $data['room_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('time_slot', $data['time_slot'])
            ->where('id', '!=', $schedule->id)
            ->exists();

        return $teacherConflict || $roomConflict;
    }
    public function clearClass(Request $request, int $kelas_id)
    {
        // Validasi kelas_id (opsional tapi disarankan)
        $kelas = Kelas::where('is_active_for_scheduling', true)->find($kelas_id);
        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan atau tidak aktif untuk penjadwalan.'
            ], 404);
        }

        // Hapus semua jadwal untuk kelas ini
        $deleted = Schedule::where('kelas_id', $kelas_id)->delete();

        return response()->json([
            'success' => true,
            'message' => "Berhasil menghapus {$deleted} jadwal untuk kelas {$kelas->nama_kelas}."
        ]);
    }

    public function fixRoomSync()
    {
        try {
            // ✅ LOG UNTUK DEBUG
            \Log::info('Fix Room Sync called by user: ' . auth()->id());
            
            $updated = DB::table('schedules')
                ->join('kelas', 'schedules.kelas_id', '=', 'kelas.id')
                ->whereColumn('schedules.room_id', '!=', 'kelas.room_id')
                ->update(['schedules.room_id' => DB::raw('kelas.room_id')]);
                
            \Log::info("Fixed {$updated} schedule rooms");
                
            return response()->json([
                'success' => true,
                'message' => "Berhasil memperbaiki {$updated} jadwal dengan ruangan yang sesuai.",
                'updated_count' => $updated
            ]);
        } catch (\Exception $e) {
            \Log::error('Fix Room Sync Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
