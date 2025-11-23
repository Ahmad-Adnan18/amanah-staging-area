<?php

namespace Database\Seeders;

use App\Models\TeacherUnavailability;
use Illuminate\Database\Seeder;

class TeacherUnavailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unavailabilities = [
            [
                'teacher_id' => 1,
                'day_of_week' => 5, // Jumat (mengikuti komentar di migrasi: 1 for Sabtu, dst)
                'time_slot' => 1,   // Jam ke-1
            ],
            [
                'teacher_id' => 2,
                'day_of_week' => 3, // Rabu
                'time_slot' => 5,   // Jam ke-5
            ],
            [
                'teacher_id' => 1,
                'day_of_week' => 1, // Senin
                'time_slot' => 7,   // Jam ke-7
            ],
        ];

        foreach ($unavailabilities as $unavailability) {
            \App\Models\TeacherUnavailability::firstOrCreate([
                'teacher_id' => $unavailability['teacher_id'],
                'day_of_week' => $unavailability['day_of_week'],
                'time_slot' => $unavailability['time_slot']
            ], $unavailability);
        }

        \App\Models\TeacherUnavailability::factory(30)->create();
    }
}