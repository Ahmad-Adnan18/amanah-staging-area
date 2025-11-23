<?php

namespace Database\Seeders;

use App\Models\JabatanUser;
use Illuminate\Database\Seeder;

class JabatanUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatanUsers = [
            [
                'user_id' => 2,
                'kelas_id' => 1,
                'jabatan_id' => 3,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'user_id' => 2,
                'kelas_id' => 2,
                'jabatan_id' => 4,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'user_id' => 1,
                'kelas_id' => 3,
                'jabatan_id' => 1,
                'tahun_ajaran' => '2025/2026',
            ],
        ];

        foreach ($jabatanUsers as $jabatanUser) {
            JabatanUser::create($jabatanUser);
        }

        JabatanUser::factory(20)->create();
    }
}