<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'target_roles',
        'user_id', // Personal Target
        'created_by',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relasi ke user target (Personal)
     */
    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke user yang membuat notifikasi
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ... (readers relation) ...

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeForRole($query, string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereNull('target_roles')
              ->orWhereJsonContains('target_roles', $role);
        });
    }

    /**
     * Scope untuk notifikasi yang visible untuk user saat ini
     * Support Global (Role) OR Personal (User ID)
     */
    public function scopeVisibleToCurrentUser($query)
    {
        $user = Auth::user();
        
        return $query->published()
            ->notExpired()
            ->where(function($q) use ($user) {
                // 1. Cek Role (Global) - HANYA JIKA user_id NULL
                $q->where(function($sub) use ($user) {
                    $sub->whereNull('user_id')
                        ->where(function($roleQ) use ($user) {
                             $roleQ->whereNull('target_roles')
                                   ->orWhereJsonContains('target_roles', $user->role);
                        });
                })
                // 2. ATAU Personal Message (User ID match)
                ->orWhere('user_id', $user->id);
            });
    }

    /**
     * Cek apakah notifikasi sudah dibaca oleh user tertentu
     */
    public function isReadBy(User $user): bool
    {
        return $this->readers()->where('user_id', $user->id)->exists();
    }

    /**
     * Cek apakah notifikasi sudah dibaca oleh user saat ini
     */
    public function isRead(): bool
    {
        return $this->isReadBy(Auth::user());
    }

    /**
     * Daftar tipe notifikasi yang tersedia
     */
    public static function getTypes(): array
    {
        return [
            'info' => 'Informasi',
            'warning' => 'Peringatan',
            'success' => 'Sukses',
            'urgent' => 'Mendesak',
        ];
    }

    /**
     * Daftar role yang bisa menerima notifikasi (exclude wali_santri)
     */
    public static function getTargetableRoles(): array
    {
        return [
            'admin' => 'Admin',
            'pengajaran' => 'Pengajaran',
            'pengasuhan' => 'Pengasuhan',
            'kesehatan' => 'Kesehatan',
            'ubudiyah' => 'Ubudiyah',
            'ustadz_umum' => 'Ustadz Umum',
            'dokumentasi' => 'Dokumentasi',
        ];
    }
}
