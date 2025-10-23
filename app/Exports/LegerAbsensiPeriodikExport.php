<?php

namespace App\Exports;

use App\Models\Santri;
use App\Models\Absensi;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LegerAbsensiPeriodikExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $startDate = \Carbon\Carbon::parse($this->filters['bulan'])->startOfMonth();
        $endDate = \Carbon\Carbon::parse($this->filters['bulan'])->endOfMonth();

        $santris = Santri::where('kelas_id', $this->filters['kelas_id'])
            ->orderBy('nama')
            ->get();

        return $santris->map(function ($santri) use ($startDate, $endDate) {
            $absensi = Absensi::where('santri_id', $santri->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->when($this->filters['schedule_id'] ?? null, function ($query, $scheduleId) {
                    return $query->where('schedule_id', $scheduleId);
                })
                ->get();

            $total = $absensi->count();
            $hadir = $absensi->where('status', 'hadir')->count();
            $presentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            return [
                'nama' => $santri->nama,
                'nis' => $santri->nis,
                'hadir' => $hadir,
                'izin' => $absensi->where('status', 'izin')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'alfa' => $absensi->where('status', 'alfa')->count(),
                'total' => $total,
                'presentase' => $presentase,
            ];
        });
    }

    public function headings(): array
    {
        $kelas = Kelas::find($this->filters['kelas_id']);
        $periode = \Carbon\Carbon::parse($this->filters['bulan'])->translatedFormat('F Y');

        return [
            ["Laporan Absensi Periodik - {$kelas->nama_kelas} - {$periode}"],
            [],
            ['Nama Santri', 'NIS', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'Total', 'Presentase (%)']
        ];
    }

    public function map($row): array
    {
        return [
            $row['nama'],
            $row['nis'],
            $row['hadir'],
            $row['izin'],
            $row['sakit'],
            $row['alfa'],
            $row['total'],
            $row['presentase'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            3 => ['font' => ['bold' => true]],
        ];
    }
}