<?php

namespace Database\Factories;

use App\Models\AppSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppSettingFactory extends Factory
{
    protected $model = AppSetting::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->word(),
            'value' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['string', 'integer', 'boolean', 'array']),
            'description' => $this->faker->sentence(),
        ];
    }
}