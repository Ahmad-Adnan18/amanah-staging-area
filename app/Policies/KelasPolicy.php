<?php
    
    namespace App\Policies;
    
    use App\Models\Kelas;
    use App\Models\User;
    
    class KelasPolicy
    {
        public function before(User $user, string $ability): bool|null
        {
            if ($user->role === 'admin') {
                return true;
            }
            return null;
        }
    
        public function viewAny(User $user): bool
        {
            return in_array($user->role, ['admin', 'pengajaran','pengasuhan','kesehatan','ustadz_umum','dokumentasi']);
        }
    
        public function create(User $user): bool
        {
            return in_array($user->role, ['admin', 'pengajaran']);
        }
    
        public function update(User $user, Kelas $kelas): bool
        {
            return in_array($user->role, ['admin', 'pengajaran']);
        }
    
        public function delete(User $user, Kelas $kelas): bool
        {
            return in_array($user->role, ['admin', 'pengajaran']);
        }
    }
    