<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SantriExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Santri::with('kelas')->get();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Alamat',
            'No. Telepon',
            'Email',
            'Kelas',
            'Rayon',
            'Asal Sekolah',
            'Nama Ayah',
            'Nama Ibu',
            'Wali ID',
            'Kode Registrasi Wali'
        ];
    }

    public function map($santri): array
    {
        return [
            $santri->nis,
            $santri->nama,
            $santri->jenis_kelamin ?? '',
            $santri->tempat_lahir ?? '',
            $santri->tanggal_lahir ?? '',
            $santri->agama ?? '',
            $santri->alamat ?? '',
            $santri->no_telepon ?? '',
            $santri->email ?? '',
            $santri->kelas->nama_kelas ?? 'N/A',
            $santri->rayon ?? '',
            $santri->asal_sekolah ?? '',
            $santri->nama_ayah ?? '',
            $santri->nama_ibu ?? '',
            $santri->wali_id ?? '',
            $santri->kode_registrasi_wali ?? ''
        ];
    }
}
