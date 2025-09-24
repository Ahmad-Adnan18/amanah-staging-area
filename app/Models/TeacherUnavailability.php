<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherUnavailability extends Model
{
    use HasFactory;

    /**
     * [PENYESUAIAN] Mengganti 'user_id' menjadi 'teacher_id' agar sesuai dengan
     * skema database yang baru dan perubahan sistem.
     */
    protected $fillable = [
        'teacher_id', 
        'day_of_week', 
        'time_slot'
    ];

    /**
     * Mendefinisikan hubungan ke model Teacher.
     */
    public function teacher(): BelongsTo
    {
        // Relasi ini sekarang akan mencari foreign key 'teacher_id' secara otomatis,
        // yang sudah cocok dengan properti $fillable di atas.
        return $this->belongsTo(Teacher::class);
    }
}

