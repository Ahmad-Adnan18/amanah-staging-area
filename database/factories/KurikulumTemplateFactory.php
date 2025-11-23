<?php

namespace Database\Factories;

use App\Models\KurikulumTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class KurikulumTemplateFactory extends Factory
{
    protected $model = KurikulumTemplate::class;

    public function definition(): array
    {
        return [
            'nama_template' => $this->faker->sentence(2),
        ];
    }
}