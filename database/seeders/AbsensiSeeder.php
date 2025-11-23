<?php

namespace Database\Seeders;

use App\Models\Absensi;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan semua foreign key merujuk ke data yang valid
        $santri = \App\Models\Santri::all();
        $kelas = \App\Models\Kelas::all();
        $schedule = \App\Models\Schedule::all();
        $teacher = \App\Models\Teacher::all();
        $user = \App\Models\User::all();
        
        if ($santri->count() == 0 || $kelas->count() == 0 || $schedule->count() == 0) {
            // Jika tidak ada data yang diperlukan, hanya gunakan factory
            Absensi::factory(100)->create();
            return;
        }
        
        $absensis = [];
        
        if ($santri->count() >= 2 && $kelas->count() >= 1 && $schedule->count() >= 3 && $teacher->count() >= 1 && $user->count() >= 1) {
            $absensis = [
                [
                    'santri_id' => $santri[0]->id,
                    'kelas_id' => $kelas[0]->id,
                    'schedule_id' => $schedule[0]->id,
                    'teacher_id' => $teacher[0]->id,
                    'tanggal' => '2025-10-20',
                    'status' => 'Hadir',
                    'keterangan' => '-',
                    'created_by' => $user[0]->id,
                    'updated_by' => $user[0]->id,
                ],
                [
                    'santri_id' => $santri[1]->id,
                    'kelas_id' => $kelas->count() > 1 ? $kelas[1]->id : $kelas[0]->id,
                    'schedule_id' => $schedule->count() > 1 ? $schedule[1]->id : $schedule[0]->id,
                    'teacher_id' => $teacher[0]->id,
                    'tanggal' => '2025-10-20',
                    'status' => 'Izin',
                    'keterangan' => 'Sakit',
                    'created_by' => $user[0]->id,
                    'updated_by' => $user[0]->id,
                ],
                [
                    'santri_id' => $santri[0]->id,
                    'kelas_id' => $kelas[0]->id,
                    'schedule_id' => $schedule->count() > 2 ? $schedule[2]->id : $schedule[0]->id,
                    'teacher_id' => $teacher[0]->id,
                    'tanggal' => '2025-10-21',
                    'status' => 'Sakit',
                    'keterangan' => 'Demam',
                    'created_by' => $user[0]->id,
                    'updated_by' => $user[0]->id,
                ],
            ];
        }

        foreach ($absensis as $absensi) {
            Absensi::create($absensi);
        }

        Absensi::factory(100)->create();
    }
}