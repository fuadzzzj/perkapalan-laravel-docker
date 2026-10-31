<?php

namespace Tests\Feature;

use App\Mail\MemberActivationMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MemberActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_approval_emails_a_single_use_activation_link(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create([
            'role' => 'anggota',
            'membership_status' => 'pending',
            'approved_at' => null,
            'activated_at' => null,
        ]);

        $this->actingAs($admin)->patch(route('admin.members.approve', $member))->assertRedirect();

        $activationUrl = null;
        Mail::assertSent(MemberActivationMail::class, function (MemberActivationMail $mail) use (&$activationUrl): bool {
            $activationUrl = $mail->activationUrl;

            return true;
        });

        $parts = explode('/', trim((string) parse_url((string) $activationUrl, PHP_URL_PATH), '/'));
        $this->assertSame('aktivasi', $parts[0] ?? null);
        $userId = (int) ($parts[1] ?? 0);
        $token = $parts[2] ?? '';
        $this->assertSame($member->id, $userId);
        $this->assertNotSame('', $token);

        $this->get(route('activation.create', ['user' => $userId, 'token' => $token]))->assertOk();
        $this->post(route('activation.store', ['user' => $userId, 'token' => $token]), [
            'password' => 'secure-long-password',
            'password_confirmation' => 'secure-long-password',
        ])->assertRedirect(route('login'));

        $member->refresh();
        $this->assertTrue($member->isActivated());
        $this->assertTrue(Hash::check('secure-long-password', $member->password));
        $this->assertDatabaseMissing('member_activation_tokens', ['user_id' => $member->id]);

        $this->post(route('activation.store', ['user' => $userId, 'token' => $token]), [
            'password' => 'another-long-password',
            'password_confirmation' => 'another-long-password',
        ])->assertSessionHasErrors('activation');
    }

    public function test_expired_activation_link_is_rejected(): void
    {
        $member = User::factory()->create([
            'role' => 'anggota',
            'membership_status' => 'approved',
            'activated_at' => null,
        ]);
        $token = 'token-that-expired';

        DB::table('member_activation_tokens')->insert([
            'user_id' => $member->id,
            'token_hash' => hash('sha256', $token),
            'expires_at' => now()->subMinute(),
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $this->get(route('activation.create', ['user' => $member->id, 'token' => $token]))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('activation');
    }

    public function test_new_registration_requires_an_email_and_starts_unactivated(): void
    {
        $this->post(route('register'), [
            'username' => 'anggota-baru',
            'email' => 'anggota-baru@example.test',
            'nama_lengkap' => 'Anggota Baru',
            'kelas' => 'XI',
            'nis' => 'NIS00000001',
            'jurusan' => 'Teknik Perkapalan',
            'tahun_masuk' => now()->year,
        ])->assertRedirect(route('login'));

        $member = User::query()->where('username', 'anggota-baru')->firstOrFail();
        $this->assertFalse($member->isApproved());
        $this->assertFalse($member->isActivated());
    }

    public function test_approved_member_cannot_sign_in_before_activation(): void
    {
        $member = User::factory()->create([
            'role' => 'anggota',
            'membership_status' => 'approved',
            'activated_at' => null,
        ]);

        $this->post(route('login'), [
            'username' => $member->username,
            'password' => 'password',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }
}
