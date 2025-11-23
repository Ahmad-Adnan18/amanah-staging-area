<?php

namespace Database\Seeders;

use App\Models\Prestasi;
use Illuminate\Database\Seeder;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prestasis = [
            [
                'santri_id' => 1,
                'nama_prestasi' => 'Juara 1 Lomba MTQ Tingkat Sekolah',
                'poin' => 50,
                'tanggal' => '2025-09-17',
                'keterangan' => 'Mewakili sekolah dalam lomba MTQ',
                'dicatat_oleh_id' => 1,
            ],
            [
                'santri_id' => 2,
                'nama_prestasi' => 'Juara 3 Lomba Kaligrafi',
                'poin' => 30,
                'tanggal' => '2025-09-20',
                'keterangan' => 'Peserta terbaik dalam lomba kaligrafi',
                'dicatat_oleh_id' => 1,
            ],
            [
                'santri_id' => 1,
                'nama_prestasi' => 'Santri Teladan',
                'poin' => 40,
                'tanggal' => '2025-10-01',
                'keterangan' => 'Santri dengan akhlak terbaik',
                'dicatat_oleh_id' => 2,
            ],
        ];

        foreach ($prestasis as $prestasi) {
            Prestasi::create($prestasi);
        }

        Prestasi::factory(20)->create();
    }
}