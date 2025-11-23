<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JabatanSeeder::class,
            TeacherSeeder::class,
            RoomSeeder::class,
            KelasSeeder::class,
            SantriSeeder::class,
            MataPelajaranSeeder::class,
            KurikulumTemplateSeeder::class,
            ScheduleSeeder::class,
            JabatanUserSeeder::class,
            TeacherUnavailabilitySeeder::class,
            PerizinanSeeder::class,
            PelanggaranSeeder::class,
            NilaiSeeder::class,
            PrestasiSeeder::class,
            CatatanHarianSeeder::class,
            RekapanHarianSeeder::class,
            RiwayatPenyakitSeeder::class,
            AbsensiSeeder::class,
            InventoryItemSeeder::class,
            BlockedTimeSeeder::class,
            HourPrioritySeeder::class,
            AppSettingSeeder::class,
            SliderItemSeeder::class,
            SuratSeeder::class,
        ]);
    }
}