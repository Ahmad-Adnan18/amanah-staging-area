<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appSettings = [
            [
                'key' => 'app_name',
                'value' => 'Sistem Perizinan Santri',
                'type' => 'string',
                'description' => 'Nama aplikasi',
            ],
            [
                'key' => 'app_version',
                'value' => '1.0.0',
                'type' => 'string',
                'description' => 'Versi aplikasi',
            ],
            [
                'key' => 'max_perizinan_count',
                'value' => '3',
                'type' => 'integer',
                'description' => 'Jumlah maksimal izin yang bisa diajukan per santri dalam sebulan',
            ],
            [
                'key' => 'semester_active',
                'value' => 'Ganjil',
                'type' => 'string',
                'description' => 'Semester aktif saat ini',
            ],
            [
                'key' => 'tahun_ajaran',
                'value' => '2025/2026',
                'type' => 'string',
                'description' => 'Tahun ajaran aktif',
            ],
        ];

        foreach ($appSettings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        AppSetting::factory(10)->create();
    }
}