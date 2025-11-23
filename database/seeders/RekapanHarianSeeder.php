<?php

namespace Database\Seeders;

use App\Models\RekapanHarian;
use Illuminate\Database\Seeder;

class RekapanHarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rekapanHarians = [
            [
                'kelas_id' => 1,
                'santri_id' => 1,
                'tanggal' => '2025-10-20',
                'jam_1' => RekapanHarian::STATUS_HADIR,
                'jam_2' => RekapanHarian::STATUS_HADIR,
                'jam_3' => RekapanHarian::STATUS_IZIN,
                'jam_4' => RekapanHarian::STATUS_HADIR,
                'jam_5' => RekapanHarian::STATUS_SAKIT,
                'jam_6' => RekapanHarian::STATUS_HADIR,
                'jam_7' => RekapanHarian::STATUS_HADIR,
                'keterangan' => 'Izin jam ke-3, sakit jam ke-5',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kelas_id' => 2,
                'santri_id' => 2,
                'tanggal' => '2025-10-20',
                'jam_1' => RekapanHarian::STATUS_HADIR,
                'jam_2' => RekapanHarian::STATUS_HADIR,
                'jam_3' => RekapanHarian::STATUS_HADIR,
                'jam_4' => RekapanHarian::STATUS_HADIR,
                'jam_5' => RekapanHarian::STATUS_HADIR,
                'jam_6' => RekapanHarian::STATUS_HADIR,
                'jam_7' => RekapanHarian::STATUS_ALFA,
                'keterangan' => 'Tanpa keterangan',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kelas_id' => 1,
                'santri_id' => 1,
                'tanggal' => '2025-10-21',
                'jam_1' => RekapanHarian::STATUS_HADIR,
                'jam_2' => RekapanHarian::STATUS_HADIR,
                'jam_3' => RekapanHarian::STATUS_HADIR,
                'jam_4' => RekapanHarian::STATUS_HADIR,
                'jam_5' => RekapanHarian::STATUS_HADIR,
                'jam_6' => RekapanHarian::STATUS_HADIR,
                'jam_7' => RekapanHarian::STATUS_HADIR,
                'keterangan' => '-',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($rekapanHarians as $rekapan) {
            RekapanHarian::create($rekapan);
        }

        RekapanHarian::factory(200)->create();
    }
}