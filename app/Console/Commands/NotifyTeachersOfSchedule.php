<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotifyTeachersOfSchedule extends Command
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
    protected $description = 'Send daily schedule notifications to teachers via FCM';

    /**
     * Execute the console command.
     */
    public function handle(FcmService $fcmService)
    {
        $this->info('Starting schedule notification process...');

        // 1. TENTUKAN HARI (Sesuai Mapping Dashboard)
        $now = Carbon::now('Asia/Jakarta');
        $dayOfWeek = $now->dayOfWeek; // 0=Sun, 1=Mon, ... 6=Sat

        // Mapping:
        // Sabtu (6) -> 1
        // Ahad (0) -> 2
        // Senin (1) -> 3
        // Selasa (2) -> 4
        // Rabu (3) -> 5
        // Kamis (4) -> 6
        // Jumat (5) -> Libur
        $dayMap = [6 => 1, 0 => 2, 1 => 3, 2 => 4, 3 => 5, 4 => 6];
        
        if (!isset($dayMap[$dayOfWeek])) {
            $this->info('Hari ini Jumat (Libur) atau hari tidak valid. Tidak ada notifikasi.');
            return;
        }

        $appDay = $dayMap[$dayOfWeek];
        $dateString = $now->locale('id')->translatedFormat('l, d F Y');

        // 2. AMBIL JADWAL HARI INI
        $schedules = Schedule::where('day_of_week', $appDay)
            ->with(['teacher.user', 'subject', 'kelas', 'room'])
            ->orderBy('time_slot')
            ->get();

        if ($schedules->isEmpty()) {
            $this->info('Tidak ada jadwal ditemukan untuk hari ini.');
            return;
        }

        // 3. KELOMPOKKAN PER GURU
        $grouped = $schedules->groupBy('teacher_id');
        $this->info("Ditemukan {$grouped->count()} guru yang memiliki jadwal.");

        $successCount = 0;
        $failCount = 0;

        // Mapping Jam (Opsional untuk tampilan)
        $jamMap = [
            1 => '07:00-07:45', 
            2 => '07:45-08:30', 
            3 => '09:00-09:45', 
            4 => '09:45-10:30',
            5 => '11:00-11:45', 
            6 => '11:45-12:30', 
            7 => '14:15-15:00'
        ];

        foreach ($grouped as $teacherId => $teacherSchedules) {
            $teacher = $teacherSchedules->first()->teacher;
            
            // Validasi: Guru punya User & User punya Token FCM
            if (!$teacher || !$teacher->user || !$teacher->user->fcm_token) {
                // $this->warn("Skip: Guru {$teacher->name ?? $teacherId} tidak memiliki User/Token FCM.");
                continue;
            }

            // 4. SUSUN PESAN NOTIFIKASI
            $title = "Jadwal Mengajar Hari Ini 🗓️";
            
            // Header Pesan
            $bodyLines = [];
            $bodyLines[] = "Assalamualaikum, {$teacher->name}";
            $bodyLines[] = "Berikut jadwal antum hari ini ({$dateString}):";
            $bodyLines[] = ""; // Spasi

            // List Jadwal
            foreach ($teacherSchedules as $sched) {
                $jam = $jamMap[$sched->time_slot] ?? "Jam ke-{$sched->time_slot}";
                $mapel = $sched->subject->nama_pelajaran ?? 'Mapel?';
                $kelas = $sched->kelas->nama_kelas ?? 'Kelas?';
                $ruang = $sched->room->name ?? 'R.?';
                
                // Format: 07:00 | Fiqih (XA) - Lab
                $bodyLines[] = "⏰ {$jam}";
                $bodyLines[] = "📘 {$mapel} - {$kelas} ({$ruang})";
                $bodyLines[] = ""; // Spasi antar jadwal
            }
            
            $bodyLines[] = "Selamat mengajar! 🚀";
            $body = implode("\n", $bodyLines);

            // 5. KIRIM VIA FCM
            try {
                $status = $fcmService->sendNotification(
                    $teacher->user->fcm_token,
                    $title,
                    $body,
                    ['type' => 'daily_schedule']
                );

                if ($status) {
                    $successCount++;
                    $this->info("✅ Terkirim ke: " . $teacher->name);
                } else {
                    $failCount++;
                    $this->error("❌ Gagal kirim ke: " . $teacher->name);
                }
            } catch (\Exception $e) {
                $failCount++;
                Log::error("FCM Command Error for {$teacher->name}: " . $e->getMessage());
            }
        }

        $this->info("Selesai. Sukses: $successCount, Gagal: $failCount");
    }
}
