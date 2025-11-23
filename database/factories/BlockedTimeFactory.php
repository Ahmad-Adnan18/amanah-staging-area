<?php

namespace Database\Factories;

use App\Models\BlockedTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlockedTimeFactory extends Factory
{
    protected $model = BlockedTime::class;

    public function definition(): array
    {
        return [
            'day_of_week' => $this->faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']),
            'time_slot' => $this->faker->time('H:i') . '-' . $this->faker->time('H:i'),
            'reason' => $this->faker->sentence(),
        ];
    }
}