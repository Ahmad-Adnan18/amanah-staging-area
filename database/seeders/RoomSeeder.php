<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['name' => 'Ruang Kelas VII-A', 'type' => 'Biasa'],
            ['name' => 'Ruang Kelas VII-B', 'type' => 'Biasa'],
            ['name' => 'Ruang Kelas VIII-A', 'type' => 'Biasa'],
            ['name' => 'Ruang Kelas VIII-B', 'type' => 'Biasa'],
            ['name' => 'Ruang Kelas IX-A', 'type' => 'Biasa'],
            ['name' => 'Ruang Kelas IX-B', 'type' => 'Biasa'],
            ['name' => 'Laboratorium IPA', 'type' => 'Khusus'],
            ['name' => 'Ruang Olahraga', 'type' => 'Khusus'],
            ['name' => 'Perpustakaan', 'type' => 'Khusus'],
            ['name' => 'Masjid', 'type' => 'Khusus'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        Room::factory(10)->create();
    }
}