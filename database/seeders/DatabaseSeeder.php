<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /**
     * Create the initial Admin and Guru accounts without replacing existing user data.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->seedPrivilegedAccount('ADMIN', 'admin');
            $this->seedPrivilegedAccount('GURU', 'guru');
        });
    }

    private function seedPrivilegedAccount(string $prefix, string $role): void
    {
        $name = trim((string) env("INITIAL_{$prefix}_NAME"));
        $username = trim((string) env("INITIAL_{$prefix}_USERNAME"));
        $email = trim((string) env("INITIAL_{$prefix}_EMAIL"));
        $password = (string) env("INITIAL_{$prefix}_PASSWORD");

        if ($name === '' || $username === '' || $email === '' || $password === '') {
            throw new RuntimeException("Set INITIAL_{$prefix}_NAME, _USERNAME, _EMAIL, and _PASSWORD before seeding.");
        }

        if (mb_strlen($password) < 12) {
            throw new RuntimeException("INITIAL_{$prefix}_PASSWORD must be at least 12 characters.");
        }

        $byUsername = User::query()->where('username', $username)->first();
        $byEmail = User::query()->where('email', $email)->first();

        if ($byEmail && (! $byUsername || $byEmail->isNot($byUsername))) {
            throw new RuntimeException("The configured {$role} email is already used by another account.");
        }

        if ($byUsername) {
            if ($byUsername->role !== $role) {
                throw new RuntimeException("The configured {$role} username already belongs to a different role.");
            }

            // Correct only access state. Preserve name, email, username, and password.
            $byUsername->forceFill([
                'membership_status' => 'approved',
                'approved_at' => $byUsername->approved_at ?? now(),
                'activated_at' => $byUsername->activated_at ?? now(),
            ])->save();

            return;
        }

        User::query()->create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'role' => $role,
            'membership_status' => 'approved',
            'approved_at' => now(),
            'activated_at' => now(),
        ]);
    }
}
