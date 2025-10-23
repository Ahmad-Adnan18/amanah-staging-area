<?php

namespace App\Policies;

use App\Models\Prestasi;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrestasiPolicy
{
    use HandlesAuthorization;

    /**
     * Helper function to check if a user is a Wali Kelas for a specific santri.
     */
    protected function isWaliKelas(User $user, Santri $santri): bool
    {
        // Cek apakah user memiliki jabatan di kelas santri tersebut untuk tahun ajaran saat ini
        return $user->jabatans()
            ->where('kelas_id', $santri->kelas_id)
            ->where('tahun_ajaran', date('Y') . '/' . (date('Y') + 1))
            ->exists();
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Wali Kelas atau pengguna dengan role pengajaran dapat membuat catatan prestasi.
     */
    public function create(User $user, Santri $santri): bool
    {
        return $this->isWaliKelas($user, $santri) || $user->role === 'pengajaran';
    }

    /**
     * Hanya user yang membuat catatan yang bisa mengeditnya.
     */
    public function update(User $user, Prestasi $prestasi): bool
    {
        return $user->id === $prestasi->dicatat_oleh_id;
    }

    /**
     * Hanya user yang membuat catatan yang bisa menghapusnya.
     */
    public function delete(User $user, Prestasi $prestasi): bool
    {
        return $user->id === $prestasi->dicatat_oleh_id;
    }
}
