<?php

namespace Database\Seeders;

use App\Models\CatatanHarian;
use Illuminate\Database\Seeder;

class CatatanHarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catatanHarians = [
            [
                'santri_id' => 1,
                'tanggal' => '2025-10-20',
                'catatan' => 'Aktif dalam kegiatan belajar mengajar',
                'dicatat_oleh_id' => 1,
            ],
            [
                'santri_id' => 2,
                'tanggal' => '2025-10-20',
                'catatan' => 'Memerlukan bimbingan tambahan dalam pelajaran matematika',
                'dicatat_oleh_id' => 1,
            ],
            [
                'santri_id' => 1,
                'tanggal' => '2025-10-21',
                'catatan' => 'Menunjukkan peningkatan dalam disiplin',
                'dicatat_oleh_id' => 2,
            ],
        ];

        foreach ($catatanHarians as $catatan) {
            CatatanHarian::create($catatan);
        }

        CatatanHarian::factory(50)->create();
    }
}