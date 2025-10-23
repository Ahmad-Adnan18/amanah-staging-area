<?php

namespace App\Exports;

use App\Models\RekapanHarian;
use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapanHarianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = RekapanHarian::where('kelas_id', $this->filters['kelas_id'])
            ->with('santri');

        if ($this->filters['start_date'] && $this->filters['end_date']) {
            $query->whereBetween('tanggal', [$this->filters['start_date'], $this->filters['end_date']]);
        }

        return $query->get()
            ->groupBy('santri_id')
            ->map(function ($rekapanSantri) {
                $santri = $rekapanSantri->first()->santri;
                $totalHadir = 0;
                $totalJam = 0;

                foreach ($rekapanSantri as $rekapan) {
                    for ($i = 1; $i <= 7; $i++) {
                        if ($rekapan->{"jam_$i"} !== \App\Models\RekapanHarian::STATUS_ALFA) {
                            $totalJam++;
                            if ($rekapan->{"jam_$i"} === \App\Models\RekapanHarian::STATUS_HADIR) {
                                $totalHadir++;
                            }
                        }
                    }
                }

                $presentase = $totalJam > 0 ? round(($totalHadir / $totalJam) * 100, 1) : 0;

                return [
                    'santri' => $santri,
                    'total_hadir' => $totalHadir,
                    'total_jam' => $totalJam,
                    'presentase' => $presentase,
                    'total_hari' => $rekapanSantri->count(),
                ];
            })->values();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Santri',
            'Total Hadir',
            'Total Jam',
            'Total Hari',
            'Presentase (%)',
            'Keterangan'
        ];
    }

    public function map($data): array
    {
        static $counter = 1;

        $keterangan = $data['presentase'] >= 90 ? 'Sangat Baik' : ($data['presentase'] >= 75 ? 'Baik' : 'Perlu Perhatian');

        return [
            $counter++,
            $data['santri']->nis,
            $data['santri']->nama,
            $data['total_hadir'],
            $data['total_jam'],
            $data['total_hari'],
            $data['presentase'],
            $keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DC2626']],
            'borders' => ['allBorders' => ['borderStyle' => 'thin']],
        ]);

        // Auto size columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Style untuk data
        $sheet->getStyle('A2:H' . ($this->collection()->count() + 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => 'thin']],
        ]);

        // Style conditional untuk presentase
        $lastRow = $this->collection()->count() + 1;
        for ($i = 2; $i <= $lastRow; $i++) {
            $presentase = $sheet->getCell('G' . $i)->getValue();
            if ($presentase >= 90) {
                $sheet->getStyle('G' . $i . ':H' . $i)->applyFromArray([
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DCFCE7']],
                ]);
            } elseif ($presentase >= 75) {
                $sheet->getStyle('G' . $i . ':H' . $i)->applyFromArray([
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FEF9C3']],
                ]);
            } else {
                $sheet->getStyle('G' . $i . ':H' . $i)->applyFromArray([
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FEE2E2']],
                ]);
            }
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Rekapan Kehadiran';
    }
}
