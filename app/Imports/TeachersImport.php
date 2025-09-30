<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeachersImport implements ToModel, WithStartRow
{
    /**
     * Metode ini akan dipanggil untuk setiap baris di file Excel.
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // 1. Mengabaikan baris kosong
        // Jika kolom A (nama guru) kosong, lewati baris ini.
        if (empty($row[0])) {
            return null;
        }

        // 2. Memetakan kolom Excel ke database
        // Kolom A (index 0) di Excel akan menjadi 'name'.
        // Kolom B (index 1) di Excel akan menjadi 'teacher_code'.
        return new Teacher([
            'name'         => $row[0],
            'teacher_code' => $row[1] ?? null, // Menggunakan ?? null agar aman jika kolom B kosong
        ]);
    }

    /**
     * Menentukan proses impor dimulai dari baris ke-2.
     * Ini berguna untuk melewati baris judul (header) di file Excel.
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}