<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkatan',
        'room_id',
        'is_active_for_scheduling',
        'kurikulum_template_id',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    public function penanggungJawab(): HasMany
    {
        return $this->hasMany(JabatanUser::class);
    }

    public function mataPelajarans(): BelongsToMany
    {
        return $this->belongsToMany(MataPelajaran::class, 'kelas_mata_pelajaran')->withPivot('teacher_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}