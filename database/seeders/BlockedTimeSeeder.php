<?php

namespace Database\Seeders;

use App\Models\BlockedTime;
use Illuminate\Database\Seeder;

class BlockedTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blockedTimes = [
            [
                'day_of_week' => 'Minggu',
                'time_slot' => '00:00-23:59',
                'reason' => 'Libur mingguan',
            ],
            [
                'day_of_week' => 'Jumat',
                'time_slot' => '11:30-13:00',
                'reason' => 'Sholat Jumat dan istirahat',
            ],
            [
                'day_of_week' => 'Senin',
                'time_slot' => '12:00-13:00',
                'reason' => 'Istirahat makan siang',
            ],
        ];

        foreach ($blockedTimes as $time) {
            BlockedTime::create($time);
        }

        BlockedTime::factory(10)->create();
    }
}