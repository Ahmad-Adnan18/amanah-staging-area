<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin Sistem',
            'password' => Hash::make('test1234'),
            'role' => 'admin',
            'teacher_code' => 'ADM001',
        ]);

        // Create teacher user if not exists
        User::firstOrCreate([
            'email' => 'guru@perizinan-santri.test',
        ], [
            'name' => 'Guru Pengajar',
            'password' => Hash::make('password'),
            'role' => 'pengajaran',
            'teacher_code' => 'GUR001',
        ]);

        // Create wali santri user if not exists
        User::firstOrCreate([
            'email' => 'wali@perizinan-santri.test',
        ], [
            'name' => 'Wali Santri',
            'password' => Hash::make('password'),
            'role' => 'pengasuhan',
            'teacher_code' => null,
        ]);

        // Create additional users
        User::factory(10)->create();
    }
}