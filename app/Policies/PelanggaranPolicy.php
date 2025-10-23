<?php

namespace App\Policies;

use App\Models\Pelanggaran;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PelanggaranPolicy
{
    use HandlesAuthorization;

    /**
     * Izinkan admin melakukan segalanya.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Semua role bisa melihat daftar pelanggaran.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Hanya admin dan pengasuhan yang bisa membuat catatan baru.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'pengasuhan', 'pengajaran']);
    }

    /**
     * Hanya admin dan pengasuhan yang bisa mengupdate.
     */
    public function update(User $user, Pelanggaran $pelanggaran): bool
    {
        return in_array($user->role, ['admin', 'pengasuhan', 'pengajaran']);
    }

    /**
     * Hanya admin dan pengasuhan yang bisa menghapus.
     */
    public function delete(User $user, Pelanggaran $pelanggaran): bool
    {
        return in_array($user->role, ['admin', 'pengasuhan', 'pengajaran']);
    }
}
