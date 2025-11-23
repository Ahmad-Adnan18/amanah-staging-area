<?php

namespace Database\Factories;

use App\Models\HourPriority;
use Illuminate\Database\Eloquent\Factories\Factory;

class HourPriorityFactory extends Factory
{
    protected $model = HourPriority::class;

    public function definition(): array
    {
        return [
            'subject_category' => $this->faker->randomElement(['Agama', 'Umum', 'Keahlian', 'Pendidikan Jasmani']),
            'day_of_week' => $this->faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
            'time_slot' => $this->faker->time('H:i') . '-' . $this->faker->time('H:i'),
            'is_allowed' => $this->faker->boolean(),
        ];
    }
}