<?php

namespace Database\Factories;

use App\Models\Absensi;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    protected $model = Absensi::class;

    public function definition(): array
    {
        return [
            'santri_id' => null,
            'kelas_id' => null,
            'schedule_id' => null,
            'teacher_id' => null,
            'tanggal' => $this->faker->date(),
            'status' => $this->faker->randomElement(['Hadir', 'Izin', 'Sakit', 'Alfa']),
            'keterangan' => $this->faker->sentence(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}