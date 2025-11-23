<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan user dengan role pengajaran untuk dihubungkan ke teacher
        $pengajaranUser = \App\Models\User::where('role', 'pengajaran')->first();
        
        \App\Models\Teacher::firstOrCreate([
            'teacher_code' => 'GUR001',
        ], [
            'name' => 'Guru Pengajar 1',
            'user_id' => $pengajaranUser ? $pengajaranUser->id : null,
        ]);

        \App\Models\Teacher::firstOrCreate([
            'teacher_code' => 'GUR002',
        ], [
            'name' => 'Guru Pengajar 2',
            'user_id' => null,
        ]);

        \App\Models\Teacher::factory(8)->create();
    }
}