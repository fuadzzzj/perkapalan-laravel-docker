<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LegacyImportPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_plaintext_legacy_password_is_hashed_and_preserved_during_import(): void
    {
        $path = storage_path('app/legacy-password-test.sql');
        file_put_contents($path, "INSERT INTO user (id, username, password, nama_lengkap, role, nis) VALUES (987, 'legacy_plain', 'plaintext-secret', 'Legacy Plain', 'siswa', 'NIS987');\n");

        try {
            $this->artisan('perkapalan:import-legacy', [
                'file' => $path,
                '--force' => true,
            ])->expectsConfirmation('Lanjutkan import? Data lama yang cocok tidak akan ditimpa; akun existing dipertahankan.', 'yes')
                ->assertExitCode(0);

            $this->artisan('perkapalan:import-legacy', [
                'file' => $path,
                '--force' => true,
            ])->expectsConfirmation('Lanjutkan import? Data lama yang cocok tidak akan ditimpa; akun existing dipertahankan.', 'yes')
                ->assertExitCode(0);

            $user = User::query()->where('username', 'legacy_plain')->firstOrFail();
            $this->assertTrue(Hash::check('plaintext-secret', $user->password));
            $this->assertNotSame('plaintext-secret', $user->password);
            $this->assertSame('anggota', $user->role);
            $this->assertTrue($user->isActivated());
            $this->assertSame(1, User::query()->where('username', 'legacy_plain')->count());
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
