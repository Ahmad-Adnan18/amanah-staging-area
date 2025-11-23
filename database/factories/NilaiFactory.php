<?php

namespace Database\Factories;

use App\Models\Nilai;
use Illuminate\Database\Eloquent\Factories\Factory;

class NilaiFactory extends Factory
{
    protected $model = Nilai::class;

    public function definition(): array
    {
        return [
            'santri_id' => null,
            'kelas_id' => null,
            'mata_pelajaran_id' => null,
            'nilai_tugas' => $this->faker->numberBetween(60, 100),
            'nilai_uts' => $this->faker->numberBetween(60, 100),
            'nilai_uas' => $this->faker->numberBetween(60, 100),
            'semester' => $this->faker->randomElement(['Ganjil', 'Genap']),
            'tahun_ajaran' => $this->faker->year() . '/' . ($this->faker->year() + 1),
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}