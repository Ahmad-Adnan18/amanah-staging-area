<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixScheduleRooms extends Command
{
    protected $signature = 'fix:schedule-rooms';
    protected $description = 'Fix schedule rooms to match class rooms';

    public function handle()
    {
        $this->info('Memulai proses fix ruangan jadwal...');
        
        $updated = DB::table('schedules')
            ->join('kelas', 'schedules.kelas_id', '=', 'kelas.id')
            ->whereColumn('schedules.room_id', '!=', 'kelas.room_id')
            ->update(['schedules.room_id' => DB::raw('kelas.room_id')]);
            
        $this->info("Berhasil memperbaiki {$updated} jadwal!");
        
        return 0;
    }
}