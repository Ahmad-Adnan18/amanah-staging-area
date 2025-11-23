<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Surat;
// Hapus: use Illuminate\Support\Facades\DB;

class SuratSeeder extends Seeder
{
    public function run(): void
    {
        // HAPUS SEMUA DB::statement() DAN truncate()
        // Ganti metodenya menjadi firstOrCreate (Aman, tidak akan menghapus data)
        // Ini hanya akan menambahkan surat jika ID-nya belum ada.

        Surat::firstOrCreate(
            ['id' => 1],
            ['nama_surat' => 'Al-Fatihah', 'jumlah_ayat' => 7]
        );

        Surat::firstOrCreate(
            ['id' => 2],
            ['nama_surat' => 'Al-Baqarah', 'jumlah_ayat' => 286]
        );

        // ... (Lanjutkan untuk 114 surat)

        Surat::firstOrCreate(
            ['id' => 114],
            ['nama_surat' => 'An-Nas', 'jumlah_ayat' => 6]
        );
    }
}