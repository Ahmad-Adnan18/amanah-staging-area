<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeachersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Teacher::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Guru',
            'Kode Guru',
            'Status Akun' // Optional: linked or not
        ];
    }

    public function map($teacher): array
    {
        return [
            $teacher->id,
            $teacher->name,
            $teacher->teacher_code,
            $teacher->user_id ? 'Terhubung' : 'Belum Terhubung',
        ];
    }
}
