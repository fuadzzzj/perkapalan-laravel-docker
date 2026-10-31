<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LegacyMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_legacy_maritime_sections(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Membangun Generasi Maritim Unggul');
        $response->assertSee('Laboratorium &amp; Praktik', false);
    }

    public function test_importer_dry_run_reports_rows_without_writing_database(): void
    {
        $path = storage_path('app/legacy-dry-run.sql');
        file_put_contents($path, "INSERT INTO user (id, username, password, nama_lengkap, role, nis) VALUES (999, 'legacy_test', 'password', 'Legacy Test', 'siswa', '999999');\n");

        $exitCode = Artisan::call('perkapalan:import-legacy', [
            'file' => $path,
            '--dry-run' => true,
        ]);

        $this->assertSame(0, $exitCode);
        $this->assertDatabaseMissing('users', ['username' => 'legacy_test']);

        unlink($path);
    }
}
