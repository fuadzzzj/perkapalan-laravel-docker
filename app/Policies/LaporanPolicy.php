<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    public function view(User $user, Laporan $laporan): bool
    {
        return $user->isAdmin() || $user->isGuru() || $laporan->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAnggota() && $user->isApproved();
    }

    public function update(User $user, Laporan $laporan): bool
    {
        return $user->isAdmin() || $user->isGuru() || $laporan->user_id === $user->id;
    }

    public function delete(User $user, Laporan $laporan): bool
    {
        return $user->isAdmin() || $laporan->user_id === $user->id;
    }
}
