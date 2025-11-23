<?php

namespace Database\Factories;

use App\Models\JabatanUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class JabatanUserFactory extends Factory
{
    protected $model = JabatanUser::class;

    public function definition(): array
    {
        return [
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->first()?->id ?: \App\Models\User::factory()->create()->id;
            },
            'kelas_id' => function () {
                return \App\Models\Kelas::inRandomOrder()->first()?->id ?: \App\Models\Kelas::factory()->create()->id;
            },
            'jabatan_id' => function () {
                return \App\Models\Jabatan::inRandomOrder()->first()?->id ?: \App\Models\Jabatan::factory()->create()->id;
            },
            'tahun_ajaran' => $this->faker->year() . '/' . ($this->faker->year() + 1),
        ];
    }
}