<?php

namespace Database\Factories;

use App\Models\Pelanggaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelanggaranFactory extends Factory
{
    protected $model = Pelanggaran::class;

    public function definition(): array
    {
        return [
            'santri_id' => null,
            'jenis_pelanggaran' => $this->faker->sentence(3),
            'tanggal_kejadian' => $this->faker->date(),
            'keterangan' => $this->faker->sentence(),
            'dicatat_oleh' => null,
        ];
    }
}