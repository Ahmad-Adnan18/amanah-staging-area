<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetoranTahfidz extends Model
{
    use HasFactory;

    protected $table = 'setoran_tahfidz';

    protected $fillable = [
        'santri_id',
        'kelas_id',
        'teacher_id',
        'tanggal_setor',
        'jenis_setoran',
        'surat_id',
        'ayat_mulai',
        'ayat_selesai',
        'nilai',
        'penerima_manual',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_setor' => 'date',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}