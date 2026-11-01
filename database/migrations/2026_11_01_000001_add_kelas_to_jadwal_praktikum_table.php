<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('jadwal_praktikum', 'kelas')) {
            Schema::table('jadwal_praktikum', function (Blueprint $table): void {
                $table->string('kelas')->nullable()->after('ruang')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('jadwal_praktikum', 'kelas')) {
            Schema::table('jadwal_praktikum', function (Blueprint $table): void {
                $table->dropIndex(['kelas']);
                $table->dropColumn('kelas');
            });
        }
    }
};
