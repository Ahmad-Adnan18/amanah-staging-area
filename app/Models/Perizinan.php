<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perizinan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'santri_id',
        'jenis_izin',
        'kategori',
        'keterangan',
        'tanggal_mulai',
        'tanggal_akhir',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_akhir' => 'date',
        ];
    }

    /**
     * Setiap perizinan dimiliki oleh satu santri.
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Setiap perizinan dibuat oleh satu user.
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Setiap perizinan diupdate oleh satu user.
     */
    public function pengupdate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
