<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapanHarian extends Model
{
    use HasFactory;

    protected $table = 'rekapan_harian';

    protected $fillable = [
        'kelas_id',
        'santri_id',
        'tanggal',
        'jam_1',
        'jam_2',
        'jam_3',
        'jam_4',
        'jam_5',
        'jam_6',
        'jam_7',
        'keterangan',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_1' => 'integer',
        'jam_2' => 'integer',
        'jam_3' => 'integer',
        'jam_4' => 'integer',
        'jam_5' => 'integer',
        'jam_6' => 'integer',
        'jam_7' => 'integer',
    ];

    // Constants untuk status
    const STATUS_ALFA = 0;
    const STATUS_HADIR = 1;
    const STATUS_SAKIT = 2;
    const STATUS_IZIN = 3;

    public static function getStatusLabels()
    {
        return [
            self::STATUS_ALFA => 'Alfa',
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_IZIN => 'Izin',
        ];
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            self::STATUS_HADIR => 'bg-green-100 text-green-800',
            self::STATUS_IZIN => 'bg-blue-100 text-blue-800',
            self::STATUS_SAKIT => 'bg-yellow-100 text-yellow-800',
            default => 'bg-red-100 text-red-800',
        };
    }

    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper methods
    public function getTotalHadir()
    {
        $total = 0;
        for ($i = 1; $i <= 7; $i++) {
            if ($this->{"jam_$i"} === self::STATUS_HADIR) {
                $total++;
            }
        }
        return $total;
    }

    public function getPresentaseKehadiran()
    {
        $totalHadir = $this->getTotalHadir();
        return $totalHadir > 0 ? round(($totalHadir / 7) * 100, 1) : 0;
    }
}
