<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan semua foreign key merujuk ke data yang valid
        $kelas = \App\Models\Kelas::all();
        $mataPelajaran = \App\Models\MataPelajaran::all();
        $teacher = \App\Models\Teacher::all();
        $room = \App\Models\Room::all();
        
        if ($kelas->count() == 0 || $mataPelajaran->count() == 0) {
            // Jika tidak ada kelas atau mata pelajaran, hanya gunakan factory
            \App\Models\Schedule::factory(50)->create();
            return;
        }
        
        // Gunakan data pertama dari masing-masing tabel atau data tergantung
        $schedules = [];
        
        if ($kelas->count() >= 2 && $mataPelajaran->count() >= 4 && $teacher->count() >= 1 && $room->count() >= 1) {
            $schedules = [
                [
                    'kelas_id' => $kelas[0]->id,
                    'mata_pelajaran_id' => $mataPelajaran[0]->id,
                    'teacher_id' => $teacher[0]->id,
                    'room_id' => $room[0]->id,
                    'day_of_week' => 1, // Senin
                    'time_slot' => 1,   // 08:00-09:30
                ],
                [
                    'kelas_id' => $kelas[1]->id,
                    'mata_pelajaran_id' => $mataPelajaran[1]->id,
                    'teacher_id' => $teacher->count() > 1 ? $teacher[1]->id : $teacher[0]->id,
                    'room_id' => $room->count() > 1 ? $room[1]->id : $room[0]->id,
                    'day_of_week' => 1, // Senin
                    'time_slot' => 2,   // 09:45-11:15
                ],
                [
                    'kelas_id' => $kelas[0]->id,
                    'mata_pelajaran_id' => $mataPelajaran[2]->id,
                    'teacher_id' => $teacher[0]->id,
                    'room_id' => $room[0]->id,
                    'day_of_week' => 2, // Selasa
                    'time_slot' => 1,   // 08:00-09:00
                ],
                [
                    'kelas_id' => $kelas->count() > 2 ? $kelas[2]->id : $kelas[0]->id,
                    'mata_pelajaran_id' => $mataPelajaran->count() > 3 ? $mataPelajaran[3]->id : $mataPelajaran[0]->id,
                    'teacher_id' => $teacher->count() > 1 ? $teacher[1]->id : $teacher[0]->id,
                    'room_id' => $room->count() > 6 ? $room[6]->id : $room[0]->id,
                    'day_of_week' => 3, // Rabu
                    'time_slot' => 3,   // 10:00-11:30
                ],
            ];
        }

        foreach ($schedules as $schedule) {
            \App\Models\Schedule::firstOrCreate([
                'kelas_id' => $schedule['kelas_id'],
                'mata_pelajaran_id' => $schedule['mata_pelajaran_id'],
                'day_of_week' => $schedule['day_of_week'],
                'time_slot' => $schedule['time_slot']
            ], $schedule);
        }

        \App\Models\Schedule::factory(50)->create();
    }
}