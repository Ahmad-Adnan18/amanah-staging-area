<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan room_id merujuk ke room yang valid
        $rooms = \App\Models\Room::all();
        if ($rooms->count() == 0) {
            // Jika tidak ada room, buat beberapa terlebih dahulu
            $rooms = collect([
                \App\Models\Room::create(['name' => 'Ruang Kelas VII-A', 'type' => 'Kelas']),
                \App\Models\Room::create(['name' => 'Ruang Kelas VII-B', 'type' => 'Kelas']),
                \App\Models\Room::create(['name' => 'Ruang Kelas VIII-A', 'type' => 'Kelas']),
                \App\Models\Room::create(['name' => 'Ruang Kelas VIII-B', 'type' => 'Kelas']),
                \App\Models\Room::create(['name' => 'Ruang Kelas IX-A', 'type' => 'Kelas']),
                \App\Models\Room::create(['name' => 'Ruang Kelas IX-B', 'type' => 'Kelas']),
            ]);
        }
        
        $kelasData = [
            ['nama_kelas' => 'VII-A', 'tingkatan' => '7', 'room_id' => $rooms[0]->id, 'is_active_for_scheduling' => true],
            ['nama_kelas' => 'VII-B', 'tingkatan' => '7', 'room_id' => $rooms[1]->id, 'is_active_for_scheduling' => true],
            ['nama_kelas' => 'VIII-A', 'tingkatan' => '8', 'room_id' => $rooms[2]->id, 'is_active_for_scheduling' => true],
            ['nama_kelas' => 'VIII-B', 'tingkatan' => '8', 'room_id' => $rooms[3]->id, 'is_active_for_scheduling' => true],
            ['nama_kelas' => 'IX-A', 'tingkatan' => '9', 'room_id' => $rooms[4]->id, 'is_active_for_scheduling' => true],
            ['nama_kelas' => 'IX-B', 'tingkatan' => '9', 'room_id' => $rooms[5]->id, 'is_active_for_scheduling' => true],
        ];

        foreach ($kelasData as $kelas) {
            \App\Models\Kelas::firstOrCreate([
                'nama_kelas' => $kelas['nama_kelas']
            ], $kelas);
        }

        \App\Models\Kelas::factory(10)->create();
    }
}