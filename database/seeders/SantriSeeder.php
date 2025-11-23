<?php

namespace Database\Seeders;

use App\Models\Santri;
use Illuminate\Database\Seeder;

class SantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan kelas_id dan wali_id merujuk ke data yang valid
        $kelas = \App\Models\Kelas::all();
        $wali = \App\Models\User::where('role', 'pengasuhan')->first();
        
        if ($kelas->count() == 0) {
            // Jika tidak ada kelas, kita lewati data spesifik dan hanya gunakan factory
            Santri::factory(52)->create();
            return;
        }
        
        $santriData = [
            [
                'nis' => '1001',
                'nama' => 'Ahmad Santri',
                'jenis_kelamin' => 'Putra',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2010-05-15',
                'agama' => 'Islam',
                'alamat' => 'Jl. Raya No. 123',
                'no_telepon' => '081234567890',
                'email' => 'ahmad@santri.test',
                'rayon' => 'Cipedes',
                'asal_sekolah' => 'SDN 1 Jakarta',
                'nama_ayah' => 'Bapak Ahmad',
                'nama_ibu' => 'Ibu Ahmad',
                'kelas_id' => $kelas[0]->id,
                'wali_id' => $wali ? $wali->id : null,
            ],
            [
                'nis' => '1002',
                'nama' => 'Siti Santri',
                'jenis_kelamin' => 'Putri',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2010-08-22',
                'agama' => 'Islam',
                'alamat' => 'Jl. Merdeka No. 45',
                'no_telepon' => '081234567891',
                'email' => 'siti@santri.test',
                'rayon' => 'Cipedes',
                'asal_sekolah' => 'SDN 2 Bandung',
                'nama_ayah' => 'Bapak Siti',
                'nama_ibu' => 'Ibu Siti',
                'kelas_id' => $kelas[1]->id,  // Gunakan kelas kedua jika tersedia
                'wali_id' => $wali ? $wali->id : null,
            ],
        ];

        foreach ($santriData as $santri) {
            \App\Models\Santri::firstOrCreate([
                'nis' => $santri['nis']
            ], $santri);
        }

        \App\Models\Santri::factory(50)->create();
    }
}