<?php

namespace Database\Factories;

use App\Models\Perizinan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerizinanFactory extends Factory
{
    protected $model = Perizinan::class;

    public function definition(): array
    {
        return [
            'santri_id' => function () {
                return \App\Models\Santri::inRandomOrder()->first()?->id ?: \App\Models\Santri::factory()->create()->id;
            },
            'jenis_izin' => $this->faker->randomElement(['Pulang', 'Keluar', 'Tidak Masuk']),
            'kategori' => $this->faker->randomElement(['Biasa', 'Darurat', 'Sakit']),
            'keterangan' => $this->faker->sentence(),
            'tanggal_mulai' => $this->faker->date(),
            'tanggal_akhir' => $this->faker->date(),
            'status' => $this->faker->randomElement(['aktif', 'selesai', 'terlambat']),
            'created_by' => function () {
                return \App\Models\User::inRandomOrder()->first()?->id ?: \App\Models\User::factory()->create()->id;
            },
            'updated_by' => function () {
                return \App\Models\User::inRandomOrder()->first()?->id ?: \App\Models\User::factory()->create()->id;
            },
        ];
    }
}