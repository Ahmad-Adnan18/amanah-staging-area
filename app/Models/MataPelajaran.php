<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MataPelajaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'mata_pelajarans';

    /**
     * Kolom yang bisa diisi secara massal.
     */
    protected $fillable = [
        'nama_pelajaran',
        'tingkatan',
        'kategori',
        'duration_jp',
        'requires_special_room',
    ];

    /**
     * [RELASI BARU]
     * Relasi ke kandidat guru yang BISA mengajar mata pelajaran ini.
     * Ini adalah "jembatan" yang hilang.
     */
    public function teachers(): BelongsToMany
    {
        // Eloquent akan mencari tabel pivot 'mata_pelajaran_teacher' secara otomatis
        return $this->belongsToMany(Teacher::class, 'mata_pelajaran_teacher');
    }
}

