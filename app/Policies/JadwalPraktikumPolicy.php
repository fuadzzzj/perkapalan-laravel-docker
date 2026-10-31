<?php

namespace App\Policies;

use App\Models\JadwalPraktikum;
use App\Models\User;

class JadwalPraktikumPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isApproved();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isGuru();
    }

    public function update(User $user, JadwalPraktikum $jadwalPraktikum): bool
    {
        return $user->isAdmin() || $jadwalPraktikum->dibuat_oleh === $user->id;
    }

    public function delete(User $user, JadwalPraktikum $jadwalPraktikum): bool
    {
        return $user->isAdmin() || $jadwalPraktikum->dibuat_oleh === $user->id;
    }
}
