<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'teacher_code','user_id'];

    /**
     * Relasi ke mata pelajaran yang bisa diajar oleh guru ini (sebagai kandidat).
     */
    public function mataPelajarans(): BelongsToMany
    {
        return $this->belongsToMany(MataPelajaran::class, 'mata_pelajaran_teacher');
    }

    /**
     * Relasi ke jadwal spesifik yang dimiliki guru ini.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Relasi ke data ketidaktersediaan yang dimiliki guru ini.
     * >> Metode inilah yang akan memperbaiki error 'unavailabilities() not found'.
     */
    public function unavailabilities(): HasMany
    {
        return $this->hasMany(TeacherUnavailability::class);
    }
    /**
     * Sebuah data Teacher bisa terhubung dengan satu akun User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

