<?php

namespace Database\Factories;

use App\Models\CatatanHarian;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatatanHarianFactory extends Factory
{
    protected $model = CatatanHarian::class;

    public function definition(): array
    {
        return [
            'santri_id' => null,
            'tanggal' => $this->faker->date(),
            'catatan' => $this->faker->paragraph(),
            'dicatat_oleh_id' => null,
        ];
    }
}