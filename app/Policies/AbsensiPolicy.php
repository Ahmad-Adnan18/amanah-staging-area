<?php

namespace App\Policies;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbsensiPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['pengajaran', 'teacher','ustadz_umum','pengasuhan','kesehatan']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['pengajaran', 'teacher','ustadz_umum','pengasuhan','kesehatan']);
    }

    public function update(User $user, Absensi $absensi): bool
    {
        // PERBAIKAN: Perbaiki logika array comparison
        $restrictedRoles = ['teacher','ustadz_umum','pengasuhan','kesehatan'];
        return in_array($user->role, ['pengajaran', 'teacher','ustadz_umum','pengasuhan','kesehatan']) &&
               (!in_array($user->role, $restrictedRoles) || $absensi->teacher_id === $user->teacher?->id);
    }

    public function delete(User $user, Absensi $absensi): bool
    {
        return $user->role === 'pengajaran'; // Hanya pengajaran bisa hapus
    }

    public function viewReport(User $user)
    {
        return in_array($user->role, ['pengajaran']); // Sesuaikan role
    }
}