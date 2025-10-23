<?php

namespace App\Policies;

use App\Models\RekapanHarian;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RekapanHarianPolicy
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
        return in_array($user->role, ['pengajaran', 'teacher', 'ustadz_umum', 'pengasuhan', 'kesehatan']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'pengajaran';
    }

    public function update(User $user, RekapanHarian $rekapan): bool
    {
        return $user->role === 'pengajaran';
    }

    public function delete(User $user, RekapanHarian $rekapan): bool
    {
        return $user->role === 'pengajaran';
    }

    public function viewReport(User $user): bool
    {
        return in_array($user->role, ['pengajaran', 'teacher', 'ustadz_umum', 'pengasuhan', 'kesehatan']);
    }
}
