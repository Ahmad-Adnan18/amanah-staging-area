<?php

namespace Database\Factories;

use App\Models\TeacherUnavailability;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherUnavailabilityFactory extends Factory
{
    protected $model = TeacherUnavailability::class;

    public function definition(): array
    {
        return [
            'teacher_id' => function () {
                return \App\Models\Teacher::inRandomOrder()->first()?->id ?: \App\Models\Teacher::factory()->create()->id;
            },
            'day_of_week' => $this->faker->numberBetween(1, 6), // 1-6 untuk Senin-Sabtu
            'time_slot' => $this->faker->numberBetween(1, 10), // Jam ke-1 hingga ke-10
        ];
    }
}