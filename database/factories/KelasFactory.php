<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition(): array
    {
        return [
            'nama_kelas' => $this->faker->regexify('[A-Z0-9]{1,2}-[A-Z]'),
            'tingkatan' => $this->faker->numberBetween(7, 9),
            'room_id' => null,
            'is_active_for_scheduling' => $this->faker->boolean(),
            'kurikulum_template_id' => null,
        ];
    }
}