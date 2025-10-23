<?php
// app/Policies/RiwayatPenyakitPolicy.php

namespace App\Policies;

use App\Models\RiwayatPenyakit;
use App\Models\User;
use App\Models\Santri;
use Illuminate\Auth\Access\Response;

class RiwayatPenyakitPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'kesehatan', 'pengasuhan']);
    }

    public function view(User $user, RiwayatPenyakit $riwayatPenyakit): bool
    {
        return in_array($user->role, ['admin', 'kesehatan', 'pengasuhan']);
    }

    // PERBAIKAN: Parameter kedua harus Santri model, bukan Santri Policy
    public function create(User $user, Santri $santri): bool
    {
        return in_array($user->role, ['admin', 'kesehatan']);
    }

    public function update(User $user, RiwayatPenyakit $riwayatPenyakit): bool
    {
        return in_array($user->role, ['admin', 'kesehatan']) ||
            $riwayatPenyakit->dicatat_oleh === $user->id;
    }

    public function delete(User $user, RiwayatPenyakit $riwayatPenyakit): bool
    {
        return in_array($user->role, ['admin', 'kesehatan']);
    }
}
