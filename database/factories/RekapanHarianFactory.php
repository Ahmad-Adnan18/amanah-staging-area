<?php

namespace Database\Factories;

use App\Models\RekapanHarian;
use Illuminate\Database\Eloquent\Factories\Factory;

class RekapanHarianFactory extends Factory
{
    protected $model = RekapanHarian::class;

    public function definition(): array
    {
        return [
            'kelas_id' => null,
            'santri_id' => null,
            'tanggal' => $this->faker->date(),
            'jam_1' => $this->faker->randomElement([0, 1, 2, 3]),
            'jam_2' => $this->faker->randomElement([0, 1, 2, 3]),
            'jam_3' => $this->faker->randomElement([0, 1, 2, 3]),
            'jam_4' => $this->faker->randomElement([0, 1, 2, 3]),
            'jam_5' => $this->faker->randomElement([0, 1, 2, 3]),
            'jam_6' => $this->faker->randomElement([0, 1, 2, 3]),
            'jam_7' => $this->faker->randomElement([0, 1, 2, 3]),
            'keterangan' => $this->faker->sentence(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}