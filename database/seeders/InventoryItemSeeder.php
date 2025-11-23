<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventoryItems = [
            [
                'room_id' => 1,
                'name' => 'Meja Guru',
                'quantity' => 1,
                'description' => 'Meja untuk guru mengajar',
            ],
            [
                'room_id' => 1,
                'name' => 'Kursi Murid',
                'quantity' => 30,
                'description' => 'Kursi untuk santri',
            ],
            [
                'room_id' => 7,
                'name' => 'Mikroskop',
                'quantity' => 5,
                'description' => 'Mikroskop untuk praktikum',
            ],
            [
                'room_id' => 8,
                'name' => 'Matras',
                'quantity' => 40,
                'description' => 'Matras untuk olahraga',
            ],
            [
                'room_id' => 9,
                'name' => 'Buku Pelajaran',
                'quantity' => 100,
                'description' => 'Koleksi buku pelajaran',
            ],
        ];

        foreach ($inventoryItems as $item) {
            InventoryItem::create($item);
        }

        InventoryItem::factory(50)->create();
    }
}