<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaranData = [
            ['nama_pelajaran' => 'Matematika', 'tingkatan' => '7-9', 'kategori' => 'Umum', 'duration_jp' => 3, 'requires_special_room' => false],
            ['nama_pelajaran' => 'Bahasa Indonesia', 'tingkatan' => '7-9', 'kategori' => 'Umum', 'duration_jp' => 3, 'requires_special_room' => false],
            ['nama_pelajaran' => 'Bahasa Inggris', 'tingkatan' => '7-9', 'kategori' => 'Umum', 'duration_jp' => 2, 'requires_special_room' => false],
            ['nama_pelajaran' => 'Ilmu Pengetahuan Alam', 'tingkatan' => '7-9', 'kategori' => 'Umum', 'duration_jp' => 3, 'requires_special_room' => true],
            ['nama_pelajaran' => 'Ilmu Pengetahuan Sosial', 'tingkatan' => '7-9', 'kategori' => 'Umum', 'duration_jp' => 2, 'requires_special_room' => false],
            ['nama_pelajaran' => 'Al-Qur\'an Hadits', 'tingkatan' => '7-9', 'kategori' => 'Diniyah', 'duration_jp' => 3, 'requires_special_room' => false],
            ['nama_pelajaran' => 'Aqidah Akhlak', 'tingkatan' => '7-9', 'kategori' => 'Diniyah', 'duration_jp' => 2, 'requires_special_room' => false],
            ['nama_pelajaran' => 'Fiqih', 'tingkatan' => '7-9', 'kategori' => 'Diniyah', 'duration_jp' => 2, 'requires_special_room' => false],
            ['nama_pelajaran' => 'Sejarah Kebudayaan Islam', 'tingkatan' => '7-9', 'kategori' => 'Diniyah', 'duration_jp' => 2, 'requires_special_room' => false],
            ['nama_pelajaran' => 'Pendidikan Jasmani', 'tingkatan' => '7-9', 'kategori' => 'Umum', 'duration_jp' => 2, 'requires_special_room' => true],
        ];

        foreach ($mataPelajaranData as $mataPelajaran) {
            \App\Models\MataPelajaran::firstOrCreate([
                'nama_pelajaran' => $mataPelajaran['nama_pelajaran']
            ], $mataPelajaran);
        }

        \App\Models\MataPelajaran::factory(10)->create();
    }
}