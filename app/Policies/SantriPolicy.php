<?php

namespace App\Policies;

use App\Models\Santri;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SantriPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * Admin bisa melakukan segalanya.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     * Semua role bisa melihat daftar santri.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Semua role bisa melihat detail santri.
     */
    public function view(User $user, Santri $santri): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * Hanya role 'pengajaran' yang bisa membuat data santri.
     */
    public function create(User $user): bool
    {
        return $user->role === 'pengajaran';
    }

    /**
     * Determine whether the user can update the model.
     * Hanya role 'pengajaran' yang bisa mengupdate data santri.
     */
    public function update(User $user, Santri $santri): bool
    {
        return in_array($user->role, ['admin', 'pengajaran', 'wali_santri']);
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya role 'pengajaran' yang bisa menghapus data santri.
     */
    public function delete(User $user, Santri $santri): bool
    {
        return $user->role === 'pengajaran';
    }
}
