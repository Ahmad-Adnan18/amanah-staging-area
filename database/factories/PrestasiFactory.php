<?php

namespace Database\Factories;

use App\Models\Prestasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrestasiFactory extends Factory
{
    protected $model = Prestasi::class;

    public function definition(): array
    {
        return [
            'santri_id' => null,
            'nama_prestasi' => $this->faker->sentence(3),
            'poin' => $this->faker->numberBetween(10, 100),
            'tanggal' => $this->faker->date(),
            'keterangan' => $this->faker->sentence(),
            'dicatat_oleh_id' => null,
        ];
    }
}