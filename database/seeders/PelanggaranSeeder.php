<?php

namespace Database\Seeders;

use App\Models\Pelanggaran;
use Illuminate\Database\Seeder;

class PelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggarans = [
            [
                'santri_id' => 1,
                'jenis_pelanggaran' => 'Terlambat',
                'tanggal_kejadian' => '2025-10-15',
                'keterangan' => 'Terlambat masuk kelas',
                'dicatat_oleh' => 1,
            ],
            [
                'santri_id' => 2,
                'jenis_pelanggaran' => 'Tidak memakai seragam lengkap',
                'tanggal_kejadian' => '2025-10-18',
                'keterangan' => 'Tidak memakai dasi',
                'dicatat_oleh' => 1,
            ],
            [
                'santri_id' => 1,
                'jenis_pelanggaran' => 'Membawa HP ke asrama',
                'tanggal_kejadian' => '2025-10-20',
                'keterangan' => 'HP disita sementara',
                'dicatat_oleh' => 2,
            ],
        ];

        foreach ($pelanggarans as $pelanggaran) {
            Pelanggaran::create($pelanggaran);
        }

        Pelanggaran::factory(30)->create();
    }
}