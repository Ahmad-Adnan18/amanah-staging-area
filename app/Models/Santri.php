<?php

namespace App\Models;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Prestasi;
use App\Models\Perizinan;
use App\Models\Pelanggaran;
use App\Models\CatatanHarian;
use App\Models\RiwayatPenyakit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'rayon',
        'foto',
        'kelas_id',
        'wali_id', // <-- Tambahkan ini
        'kode_registrasi_wali',
    ];

    /**
     * Relasi ke Kelas: Setiap santri 'milik' satu kelas.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi ke Perizinan: Setiap santri bisa memiliki BANYAK izin.
     * Nama method ini (perizinans) harus jamak.
     */
    public function perizinans(): HasMany
    {
        return $this->hasMany(Perizinan::class);
    }

    /**
     * Relasi ke Pelanggaran: Setiap santri bisa memiliki BANYAK pelanggaran.
     * Nama method ini (pelanggarans) harus jamak.
     */
    public function pelanggarans(): HasMany
    {
        return $this->hasMany(Pelanggaran::class);
    }

    /**
     * Relasi ke Nilai: Setiap santri bisa memiliki BANYAK nilai.
     * Nama method 'nilai' (singular) digunakan agar cocok dengan
     * pemanggilan di controller ->with('nilai').
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function catatanHarians(): HasMany
    {
        return $this->hasMany(CatatanHarian::class);
    }

    public function prestasis(): HasMany
    {
        return $this->hasMany(Prestasi::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    // TAMBAHAN: Relasi Riwayat Penyakit
    public function riwayatPenyakits(): HasMany
    {
        return $this->hasMany(RiwayatPenyakit::class);
    }
}
