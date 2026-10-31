<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Process\Process;
use Throwable;

#[Signature('perkapalan:import-legacy
    {file : Path ke SQL dump lama}
    {--dry-run : Analisis tanpa menulis database}
    {--backup : Buat backup database baru sebelum impor}
    {--force : Lanjutkan impor meski ada data lama}
')]
#[Description('Impor data SQL lama secara terbatas, aman, dan tanpa menimpa akun')]
class ImportLegacyPerkapalan extends Command
{
    private const ALLOWED_TABLES = ['user', 'laporan', 'nilai_praktikum', 'jadwal_praktikum', 'pengumuman', 'chat'];

    public function handle(): int
    {
        $file = (string) $this->argument('file');
        $path = str_starts_with($file, '/') ? $file : base_path($file);

        if (! is_file($path) || ! is_readable($path)) {
            $this->error("File SQL tidak ditemukan atau tidak dapat dibaca: {$path}");

            return self::FAILURE;
        }

        $sql = file_get_contents($path);
        if ($sql === false) {
            $this->error('File SQL gagal dibaca.');

            return self::FAILURE;
        }

        $statements = $this->parseInsertStatements($sql);
        $known = array_values(array_filter($statements, fn (array $statement): bool => in_array($statement['table'], self::ALLOWED_TABLES, true)));

        if ($known === []) {
            $this->warn('Tidak ditemukan INSERT untuk tabel lama yang didukung. Tidak ada data ditulis.');

            return self::SUCCESS;
        }

        $this->info('Ringkasan dry-run / analisis file SQL:');
        foreach ($known as $statement) {
            $this->line("- {$statement['table']}: ".count($statement['rows']).' baris');
        }

        if ($this->option('dry-run') || ! $this->option('force')) {
            $this->comment('Tidak ada perubahan database. Gunakan --force dan tanpa --dry-run untuk menjalankan import.');

            return self::SUCCESS;
        }

        if (! $this->confirm('Lanjutkan import? Data lama yang cocok tidak akan ditimpa; akun existing dipertahankan.', false)) {
            $this->warn('Import dibatalkan.');

            return self::SUCCESS;
        }

        if ($this->option('backup')) {
            if (! $this->createBackup()) {
                return self::FAILURE;
            }
        }

        try {
            DB::transaction(function () use ($known): void {
                $this->importStatements($known);
            });
        } catch (Throwable $exception) {
            $this->error('Import dibatalkan: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Import selesai. Semua perubahan dilakukan dalam satu transaction dan data existing dipertahankan.');

        return self::SUCCESS;
    }

    private function createBackup(): bool
    {
        $backupDirectory = storage_path('app/backups');
        if (! is_dir($backupDirectory)) {
            mkdir($backupDirectory, 0755, true);
        }

        $backupPath = $backupDirectory.'/perkapalan-before-import-'.now()->format('Ymd-His').'.sql';
        $process = new Process([
            'mysqldump',
            '--host='.config('database.connections.mysql.host'),
            '--port='.config('database.connections.mysql.port'),
            '--user='.config('database.connections.mysql.username'),
            '--password='.config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
        ]);
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Backup gagal: '.$process->getErrorOutput());

            return false;
        }

        file_put_contents($backupPath, $process->getOutput());
        $this->info("Backup dibuat: {$backupPath}");

        return true;
    }

    /** @return array<int, array{table:string, rows:array<int, array<string, mixed>>}> */
    private function parseInsertStatements(string $sql): array
    {
        $statements = [];
        $pattern = '/INSERT\s+INTO\s+[`]?([^`\s(]+)[`]?(?:\s*\(([^)]*)\))?\s+VALUES\s*(.+?);/is';

        if (preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER) === false) {
            return [];
        }

        foreach ($matches as $match) {
            $table = strtolower(str_replace(['`'], '', $match[1]));
            if (! in_array($table, self::ALLOWED_TABLES, true)) {
                continue;
            }

            $columns = isset($match[2]) && trim($match[2]) !== ''
                ? array_map(fn (string $column): string => trim(str_replace('`', '', $column)), explode(',', $match[2]))
                : [];
            if ($columns === []) {
                continue;
            }

            foreach ($this->splitSqlRows($match[3]) as $rowText) {
                $values = $this->splitSqlValues(trim($rowText, '() '));
                if (count($values) !== count($columns)) {
                    continue;
                }

                $row = [];
                foreach ($columns as $index => $column) {
                    $row[$column] = $this->parseSqlValue($values[$index]);
                }
                $statements[] = ['table' => $table, 'rows' => [$row]];
            }
        }

        $combined = [];
        foreach ($statements as $statement) {
            $table = $statement['table'];
            $combined[$table] ??= ['table' => $table, 'rows' => []];
            $combined[$table]['rows'][] = $statement['rows'][0];
        }

        return array_values($combined);
    }

    /** @return array<int, string> */
    private function splitSqlRows(string $values): array
    {
        $rows = [];
        $start = null;
        $depth = 0;
        $quote = null;
        $escaped = false;
        $length = strlen($values);

        for ($index = 0; $index < $length; $index++) {
            $character = $values[$index];
            if ($escaped) {
                $escaped = false;

                continue;
            }
            if ($character === '\\' && $quote !== null) {
                $escaped = true;

                continue;
            }
            if (($character === "'" || $character === '"') && $quote === null) {
                $quote = $character;

                continue;
            }
            if ($character === $quote) {
                $quote = null;

                continue;
            }
            if ($quote !== null) {
                continue;
            }
            if ($character === '(') {
                if ($depth === 0) {
                    $start = $index;
                }
                $depth++;
            } elseif ($character === ')') {
                $depth--;
                if ($depth === 0 && $start !== null) {
                    $rows[] = substr($values, $start, $index - $start + 1);
                    $start = null;
                }
            }
        }

        return $rows;
    }

    /** @return array<int, string> */
    private function splitSqlValues(string $values): array
    {
        $parts = [];
        $start = 0;
        $depth = 0;
        $quote = null;
        $escaped = false;
        $length = strlen($values);

        for ($index = 0; $index < $length; $index++) {
            $character = $values[$index];
            if ($escaped) {
                $escaped = false;

                continue;
            }
            if ($character === '\\' && $quote !== null) {
                $escaped = true;

                continue;
            }
            if (($character === "'" || $character === '"') && $quote === null) {
                $quote = $character;

                continue;
            }
            if ($character === $quote) {
                $quote = null;

                continue;
            }
            if ($quote !== null) {
                continue;
            }
            if ($character === '(') {
                $depth++;
            } elseif ($character === ')') {
                $depth--;
            } elseif ($character === ',' && $depth === 0) {
                $parts[] = trim(substr($values, $start, $index - $start));
                $start = $index + 1;
            }
        }
        $parts[] = trim(substr($values, $start));

        return $parts;
    }

    private function parseSqlValue(string $value): mixed
    {
        $value = trim($value);
        if (strtoupper($value) === 'NULL') {
            return null;
        }
        if (strlen($value) >= 2 && $value[0] === "'" && $value[strlen($value) - 1] === "'") {
            return str_replace(["\\'", '\\\\'], ["'", '\\'], substr($value, 1, -1));
        }
        if (is_numeric($value)) {
            return str_contains($value, '.') ? (float) $value : (int) $value;
        }

        return $value;
    }

    /** @param array<int, array{table:string, rows:array<int, array<string, mixed>>}> $statements */
    private function importStatements(array $statements): void
    {
        $userMap = [];

        foreach ($statements as $statement) {
            if ($statement['table'] === 'user') {
                foreach ($statement['rows'] as $row) {
                    $legacyId = $row['id'] ?? null;
                    $username = trim((string) ($row['username'] ?? ''));
                    $nis = trim((string) ($row['nis'] ?? ''));
                    if ($username === '') {
                        continue;
                    }

                    $existing = DB::table('users')->where('username', $username)->when($nis !== '', fn ($query) => $query->orWhere('nis', $nis))->first();
                    if ($existing) {
                        if ($legacyId !== null) {
                            DB::table('users')->where('id', $existing->id)->whereNull('legacy_source_id')->update(['legacy_source_id' => 'user:'.$legacyId]);
                            $userMap[(int) $legacyId] = (int) $existing->id;
                        }

                        continue;
                    }

                    $role = match ($row['role'] ?? 'siswa') {
                        'guru' => 'guru',
                        'admin' => 'admin',
                        default => 'anggota',
                    };
                    $nama = $row['nama_lengkap'] ?? $row['name'] ?? $username;
                    $password = (string) ($row['password'] ?? '');
                    if (! str_starts_with($password, '$2y$') && ! str_starts_with($password, '$argon')) {
                        // Legacy registration stored password_hash() values while older rows may be plaintext.
                        // Preserve the credential without ever carrying plaintext into the new database.
                        $password = Hash::make($password);
                    }

                    $newId = DB::table('users')->insertGetId([
                        'legacy_source_id' => $legacyId !== null ? 'user:'.$legacyId : null,
                        'username' => $username,
                        'name' => $nama,
                        'nama_lengkap' => $nama,
                        'nis' => $nis !== '' ? $nis : null,
                        'kelas' => $row['kelas'] ?? null,
                        'jurusan' => $row['jurusan'] ?? null,
                        'tahun_masuk' => $row['tahun_masuk'] ?? null,
                        'email' => ! empty($row['email']) ? $row['email'] : null,
                        'password' => $password,
                        'role' => $role,
                        'membership_status' => $role === 'anggota' ? 'approved' : 'approved',
                        'approved_at' => now(),
                        'activated_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    if ($legacyId !== null) {
                        $userMap[(int) $legacyId] = $newId;
                    }
                }
            }
        }

        foreach ($statements as $statement) {
            foreach ($statement['rows'] as $row) {
                $this->importDataRow($statement['table'], $row, $userMap);
            }
        }
    }

    /** @param array<string, mixed> $row @param array<int, int> $userMap */
    private function importDataRow(string $table, array $row, array $userMap): void
    {
        if ($table === 'laporan') {
            $userId = $userMap[(int) ($row['user_id'] ?? 0)] ?? null;
            if ($userId === null) {
                return;
            }
            $legacySourceId = 'laporan:'.($row['id'] ?? $row['judul']);
            if (! DB::table('laporan')->where('legacy_source_id', $legacySourceId)->exists()) {
                DB::table('laporan')->insert([
                    'legacy_source_id' => $legacySourceId,
                    'user_id' => $userId,
                    'judul' => $row['judul'] ?? 'Laporan lama',
                    'status' => in_array($row['status'] ?? '', ['menunggu', 'disetujui', 'revisi'], true) ? $row['status'] : 'menunggu',
                    'tanggal_kirim' => $row['tanggal_kirim'] ?? now()->toDateString(),
                    'file_path' => $row['file_path'] ?? '',
                    'created_at' => $row['created_at'] ?? now(),
                    'updated_at' => $row['updated_at'] ?? now(),
                ]);
            }
        } elseif ($table === 'nilai_praktikum') {
            $userId = $userMap[(int) ($row['user_id'] ?? 0)] ?? null;
            if ($userId !== null) {
                $legacySourceId = 'nilai_praktikum:'.($row['id'] ?? md5(json_encode($row)));
                if (! DB::table('nilai_praktikum')->where('legacy_source_id', $legacySourceId)->exists()) {
                    DB::table('nilai_praktikum')->insert([
                        'legacy_source_id' => $legacySourceId,
                        'user_id' => $userId,
                        'mata_pelajaran' => $row['mata_pelajaran'] ?? '-',
                        'nilai' => $row['nilai'] ?? 0,
                        'semester' => $row['semester'] ?? null,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
        } elseif ($table === 'jadwal_praktikum') {
            $creatorId = $userMap[(int) ($row['dibuat_oleh'] ?? 0)] ?? DB::table('users')->whereIn('role', ['admin', 'guru'])->value('id');
            if ($creatorId === null) {
                return;
            }
            $legacySourceId = 'jadwal_praktikum:'.($row['id'] ?? md5(json_encode($row)));
            if (! DB::table('jadwal_praktikum')->where('legacy_source_id', $legacySourceId)->exists()) {
                DB::table('jadwal_praktikum')->insert([
                    'legacy_source_id' => $legacySourceId,
                    'mata_pelajaran' => $row['mata_pelajaran'] ?? '-',
                    'tanggal' => $row['tanggal'] ?? now()->toDateString(),
                    'jam' => $row['jam'] ?? '08:00:00',
                    'ruang' => $row['ruang'] ?? '-',
                    'foto_path' => $row['foto_path'] ?? null,
                    'dibuat_oleh' => $creatorId,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        } elseif ($table === 'pengumuman') {
            $creatorId = $userMap[(int) ($row['dibuat_oleh'] ?? 0)] ?? DB::table('users')->whereIn('role', ['admin', 'guru'])->value('id');
            if ($creatorId === null) {
                return;
            }
            $legacySourceId = 'pengumuman:'.($row['id'] ?? md5(json_encode($row)));
            if (! DB::table('pengumuman')->where('legacy_source_id', $legacySourceId)->exists()) {
                DB::table('pengumuman')->insert([
                    'legacy_source_id' => $legacySourceId,
                    'judul' => $row['judul'] ?? '-',
                    'isi' => $row['isi'] ?? '',
                    'dibuat_oleh' => $creatorId,
                    'created_at' => $row['created_at'] ?? now(), 'updated_at' => $row['updated_at'] ?? now(),
                ]);
            }
        } elseif ($table === 'chat') {
            $userId = $userMap[(int) ($row['user_id'] ?? 0)] ?? null;
            if ($userId === null) {
                return;
            }
            $mediaType = $row['media_type'] ?? null;
            $legacySourceId = 'chat:'.($row['id'] ?? md5(json_encode($row)));
            if (! DB::table('chat')->where('legacy_source_id', $legacySourceId)->exists()) {
                DB::table('chat')->insert([
                    'legacy_source_id' => $legacySourceId,
                    'user_id' => $userId,
                    'pesan' => $row['pesan'] ?? null,
                    'media_path' => $row['media_path'] ?? null,
                    'media_type' => in_array($mediaType, ['image', 'video', 'audio', 'document'], true) ? $mediaType : null,
                    'created_at' => $row['created_at'] ?? now(), 'updated_at' => $row['updated_at'] ?? now(),
                ]);
            }
        }
    }
}
