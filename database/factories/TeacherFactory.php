<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'teacher_code' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'user_id' => null,
        ];
    }
}