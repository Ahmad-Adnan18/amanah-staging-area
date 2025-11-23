<?php

namespace Database\Factories;

use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class SantriFactory extends Factory
{
    protected $model = Santri::class;

    public function definition(): array
    {
        return [
            'nis' => $this->faker->unique()->numerify('########'),
            'nama' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['Putra', 'Putri']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-13 years'),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'alamat' => $this->faker->address(),
            'no_telepon' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'rayon' => $this->faker->word(),
            'asal_sekolah' => $this->faker->sentence(3),
            'nama_ayah' => $this->faker->name(),
            'nama_ibu' => $this->faker->name(),
            'kelas_id' => function () {
                return \App\Models\Kelas::inRandomOrder()->first()?->id ?: \App\Models\Kelas::factory()->create()->id;
            },
            'wali_id' => null,
            'kode_registrasi_wali' => $this->faker->uuid(),
        ];
    }
}