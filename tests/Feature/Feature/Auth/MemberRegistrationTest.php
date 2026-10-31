<?php

namespace Tests\Feature\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_member_registration_is_pending(): void
    {
        $response = $this->post('/register', [
            'username' => 'taruna1',
            'email' => 'taruna1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nama_lengkap' => 'Taruna Satu',
            'kelas' => 'XI',
            'nis' => '123456789',
            'jurusan' => 'Teknik Perkapalan',
            'tahun_masuk' => now()->year,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', [
            'username' => 'taruna1',
            'role' => 'anggota',
            'membership_status' => 'pending',
        ]);

        $user = User::where('username', 'taruna1')->firstOrFail();
        $this->assertGuest();
        $this->assertFalse($user->isApproved());
    }
}
