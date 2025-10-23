<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPenyakit extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'nama_penyakit',
        'keterangan',
        'tanggal_diagnosis',
        'status',
        'penanganan',
        'dicatat_oleh'
    ];

    protected $casts = [
        'tanggal_diagnosis' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
