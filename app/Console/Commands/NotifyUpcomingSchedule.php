<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotifyUpcomingSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:notify-teachers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send FCM notification to teachers 10 minutes before class starts';

    /**
     * Execute the console command.
     */
    public function handle(FcmService $fcmService)
    {
        $now = Carbon::now('Asia/Jakarta');
        
        // Kita cek untuk jadwal 10 menit ke depan
        $targetTime = $now->copy()->addMinutes(10)->format('H:i');
        
        // Logika Hari (Sama dengan DashboardController)
        $dayMap = [6 => 1, 0 => 2, 1 => 3, 2 => 4, 3 => 5, 4 => 6];
        $currentDayId = $dayMap[$now->dayOfWeek] ?? null;

        if (!$currentDayId) {
            $this->info('Hari ini libur/tidak ada jadwal (Jumat/Minggu diluar mapping).');
            return;
        }

        // Logic Jam Pelajaran (Sama dengan DashboardController)
        $jamMap = [
            1 => ['start' => '07:00'],
            2 => ['start' => '07:45'],
            3 => ['start' => '09:00'],
            4 => ['start' => '09:45'],
            5 => ['start' => '11:00'],
            6 => ['start' => '11:45'],
            7 => ['start' => '14:15'],
        ];

        // Cari slot mana yang mulai di $targetTime
        $upcomingSlot = null;
        foreach ($jamMap as $slot => $time) {
            if ($time['start'] === $targetTime) {
                $upcomingSlot = $slot;
                break;
            }
        }

        if (!$upcomingSlot) {
            $this->info("Tidak ada jam pelajaran yang mulai pukul {$targetTime}");
            return;
        }

        $this->info("Mengecek jadwal jam ke-{$upcomingSlot} (Mulai {$targetTime})...");

        // Ambil Jadwal di DB
        $schedules = Schedule::where('day_of_week', $currentDayId)
            ->where('time_slot', $upcomingSlot)
            ->with(['teacher.user', 'subject', 'kelas', 'room'])
            ->get();

        $count = 0;
        foreach ($schedules as $schedule) {
            $teacher = $schedule->teacher;
            if (!$teacher || !$teacher->user || !$teacher->user->fcm_token) {
                continue;
            }

            $title = "Waktunya Mengajar! 📚";
            $body = "Ustadz, 10 menit lagi mengajar {$schedule->subject->nama_pelajaran} di kelas {$schedule->kelas->nama_kelas} ({$schedule->room->name}).";
            
            try {
                $fcmService->broadcast(
                    $title, 
                    $body, 
                    $teacher->user->fcm_token,
                    ['notification_id' => 'schedule-' . $schedule->id]
                );
                
                $this->info("Notif dikirim ke: {$teacher->user->name}");
                $count++;
            } catch (\Exception $e) {
                Log::error("Gagal kirim notif jadwal ke {$teacher->user->name}: " . $e->getMessage());
            }
        }

        $this->info("Selesai. Terkirim: {$count}");
    }
}
