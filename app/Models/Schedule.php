<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'mata_pelajaran_id',
        'teacher_id',
        'room_id',
        'day_of_week',
        'time_slot',
    ];

    /**
     * Relasi ke model Kelas.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke model MataPelajaran.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    /**
     * [PENYESUAIAN WAJIB]
     * Mengubah relasi 'teacher' agar menunjuk ke model Teacher yang baru.
     * Ini akan secara otomatis memperbaiki tampilan di halaman Lihat Jadwal dan Tukar Jadwal.
     */
    public function teacher(): BelongsTo
    {
        // Ganti User::class menjadi Teacher::class
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Relasi ke model Room.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
