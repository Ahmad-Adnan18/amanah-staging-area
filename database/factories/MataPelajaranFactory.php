<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataPelajaranFactory extends Factory
{
    protected $model = MataPelajaran::class;

    public function definition(): array
    {
        return [
            'nama_pelajaran' => $this->faker->words(2, true),
            'tingkatan' => $this->faker->randomElement(['7', '8', '9', '7-9']),
            'kategori' => $this->faker->randomElement(['Umum', 'Diniyah']),
            'duration_jp' => $this->faker->numberBetween(1, 4),
            'requires_special_room' => $this->faker->boolean(),
        ];
    }
}