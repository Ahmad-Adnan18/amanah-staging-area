<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LegerAbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;
    protected $kelas;
    protected $schedule;

    public function __construct(array $filters, Kelas $kelas, Schedule $schedule)
    {
        $this->filters = $filters;
        $this->kelas = $kelas;
        $this->schedule = $schedule;
    }

    public function collection()
    {
        return Absensi::where('kelas_id', $this->filters['kelas_id'])
            ->where('schedule_id', $this->filters['schedule_id'])
            ->where('tanggal', $this->filters['tanggal'])
            ->with('santri')
            ->orderBy('santri_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Santri',
            'Status',
            'Keterangan',
            'Tanggal',
            'Mata Pelajaran',
            'Kelas',
        ];
    }

    public function map($absensi): array
    {
        return [
            $absensi->santri->nis,
            $absensi->santri->nama,
            ucfirst($absensi->status),
            $absensi->keterangan ?? '-',
            $absensi->tanggal->format('Y-m-d'),
            $this->schedule->subject->nama_pelajaran,
            $this->kelas->nama_kelas,
        ];
    }
}