<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminBootstrapCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_admin_can_be_created_with_a_hashed_password(): void
    {
        $this->artisan('perkapalan:make-admin')
            ->expectsQuestion('Nama lengkap admin', 'Admin Pertama')
            ->expectsQuestion('Username admin', 'admin-pertama')
            ->expectsQuestion('Email admin', 'admin@example.test')
            ->expectsQuestion('Password admin (minimal 12 karakter)', 'admin-secure-password')
            ->expectsQuestion('Ulangi password admin', 'admin-secure-password')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'username' => 'admin-pertama',
            'role' => 'admin',
            'membership_status' => 'approved',
        ]);

        $this->assertTrue(Hash::check('admin-secure-password', User::query()->where('username', 'admin-pertama')->firstOrFail()->password));
    }
}
