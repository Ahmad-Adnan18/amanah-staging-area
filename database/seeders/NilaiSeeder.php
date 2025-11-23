<?php

namespace Database\Seeders;

use App\Models\Nilai;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nilais = [
            [
                'santri_id' => 1,
                'kelas_id' => 1,
                'mata_pelajaran_id' => 1,
                'nilai_tugas' => 85,
                'nilai_uts' => 80,
                'nilai_uas' => 88,
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2025/2026',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'santri_id' => 2,
                'kelas_id' => 2,
                'mata_pelajaran_id' => 2,
                'nilai_tugas' => 90,
                'nilai_uts' => 85,
                'nilai_uas' => 92,
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2025/2026',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'santri_id' => 1,
                'kelas_id' => 1,
                'mata_pelajaran_id' => 2,
                'nilai_tugas' => 78,
                'nilai_uts' => 82,
                'nilai_uas' => 85,
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2025/2026',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($nilais as $nilai) {
            Nilai::create($nilai);
        }

        Nilai::factory(200)->create();
    }
}