<?php

namespace Database\Seeders;

use App\Models\HourPriority;
use Illuminate\Database\Seeder;

class HourPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hourPriorities = [
            [
                'subject_category' => 'Agama',
                'day_of_week' => 'Senin',
                'time_slot' => '08:00-09:30',
                'is_allowed' => true,
            ],
            [
                'subject_category' => 'Umum',
                'day_of_week' => 'Selasa',
                'time_slot' => '10:00-11:30',
                'is_allowed' => true,
            ],
            [
                'subject_category' => 'Pendidikan Jasmani',
                'day_of_week' => 'Rabu',
                'time_slot' => '14:00-15:30',
                'is_allowed' => true,
            ],
            [
                'subject_category' => 'Agama',
                'day_of_week' => 'Kamis',
                'time_slot' => '09:00-10:30',
                'is_allowed' => true,
            ],
        ];

        foreach ($hourPriorities as $priority) {
            HourPriority::create($priority);
        }

        HourPriority::factory(25)->create();
    }
}