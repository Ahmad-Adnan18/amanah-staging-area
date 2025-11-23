<?php

namespace Database\Factories;

use App\Models\RiwayatPenyakit;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiwayatPenyakitFactory extends Factory
{
    protected $model = RiwayatPenyakit::class;

    public function definition(): array
    {
        return [
            'santri_id' => null,
            'nama_penyakit' => $this->faker->word(),
            'keterangan' => $this->faker->sentence(),
            'tanggal_diagnosis' => $this->faker->date(),
            'status' => $this->faker->randomElement(['Sembuh', 'Kronis', 'Dalam Perawatan']),
            'penanganan' => $this->faker->sentence(),
            'dicatat_oleh' => null,
        ];
    }
}