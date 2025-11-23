<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatanNames = [
            'Kepala Sekolah',
            'Wakil Kepala Sekolah',
            'Guru Kelas',
            'Wali Kelas',
            'Pembimbing Akademik',
            'Pembimbing Spiritual',
            'Pembimbing Disiplin',
        ];

        foreach ($jabatanNames as $jabatanName) {
            \App\Models\Jabatan::firstOrCreate([
                'nama_jabatan' => $jabatanName
            ]);
        }
    }
}