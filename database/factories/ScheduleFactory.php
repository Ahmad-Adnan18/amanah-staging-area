<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'kelas_id' => function () {
                return \App\Models\Kelas::inRandomOrder()->first()?->id ?: \App\Models\Kelas::factory()->create()->id;
            },
            'mata_pelajaran_id' => function () {
                return \App\Models\MataPelajaran::inRandomOrder()->first()?->id ?: \App\Models\MataPelajaran::factory()->create()->id;
            },
            'teacher_id' => function () {
                return \App\Models\Teacher::inRandomOrder()->first()?->id ?: \App\Models\Teacher::factory()->create()->id;
            },
            'room_id' => function () {
                return \App\Models\Room::inRandomOrder()->first()?->id ?: \App\Models\Room::factory()->create()->id;
            },
            'day_of_week' => $this->faker->numberBetween(1, 6), // 1-6 untuk Senin-Sabtu
            'time_slot' => $this->faker->numberBetween(1, 10), // 1-10 untuk slot waktu
        ];
    }
}