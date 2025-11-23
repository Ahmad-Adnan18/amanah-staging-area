<?php

namespace Database\Seeders;

use App\Models\Perizinan;
use Illuminate\Database\Seeder;

class PerizinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan semua foreign key merujuk ke data yang valid
        $santri = \App\Models\Santri::all();
        $user = \App\Models\User::all();
        
        if ($santri->count() == 0) {
            // Jika tidak ada santri, hanya gunakan factory
            Perizinan::factory(50)->create();
            return;
        }
        
        $perizinans = [];
        
        if ($santri->count() >= 2 && $user->count() >= 1) {
            $perizinans = [
                [
                    'santri_id' => $santri[0]->id,
                    'jenis_izin' => 'Pulang',
                    'kategori' => 'Biasa',
                    'keterangan' => 'Izin menghadiri acara keluarga',
                    'tanggal_mulai' => '2025-10-25',
                    'tanggal_akhir' => '2025-10-26',
                    'status' => 'aktif',
                    'created_by' => $user[0]->id,
                    'updated_by' => $user[0]->id,
                ],
                [
                    'santri_id' => $santri[1]->id,
                    'jenis_izin' => 'Keluar',
                    'kategori' => 'Darurat',
                    'keterangan' => 'Dipanggil orang tua',
                    'tanggal_mulai' => '2025-10-22',
                    'tanggal_akhir' => '2025-10-22',
                    'status' => 'selesai',
                    'created_by' => $user[0]->id,
                    'updated_by' => $user[0]->id,
                ],
                [
                    'santri_id' => $santri[0]->id,
                    'jenis_izin' => 'Tidak Masuk',
                    'kategori' => 'Sakit',
                    'keterangan' => 'Sakit demam',
                    'tanggal_mulai' => '2025-10-18',
                    'tanggal_akhir' => '2025-10-19',
                    'status' => 'aktif',
                    'created_by' => $user[0]->id,
                    'updated_by' => $user[0]->id,
                ],
            ];
        }

        foreach ($perizinans as $perizinan) {
            \App\Models\Perizinan::firstOrCreate([
                'santri_id' => $perizinan['santri_id'],
                'tanggal_mulai' => $perizinan['tanggal_mulai']
            ], $perizinan);
        }

        \App\Models\Perizinan::factory(50)->create();
    }
}