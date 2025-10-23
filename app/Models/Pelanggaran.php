<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'santri_id',
        'jenis_pelanggaran',
        'tanggal_kejadian',
        'keterangan',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
        ];
    }

    /**
     * Setiap catatan pelanggaran dimiliki oleh satu santri.
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
}
