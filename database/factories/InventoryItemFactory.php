<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'room_id' => null,
            'name' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1, 50),
            'description' => $this->faker->sentence(),
        ];
    }
}