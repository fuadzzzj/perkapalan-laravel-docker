<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['laporan', 'nilai_praktikum', 'jadwal_praktikum', 'pengumuman', 'chat'] as $tableName) {
            if (! Schema::hasColumn($tableName, 'legacy_source_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('legacy_source_id')->nullable()->after('id')->index();
                });
            }
        }

        if (! Schema::hasColumn('users', 'legacy_source_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('legacy_source_id')->nullable()->after('id')->index();
            });
        }
    }

    public function down(): void
    {
        foreach (['users', 'laporan', 'nilai_praktikum', 'jadwal_praktikum', 'pengumuman', 'chat'] as $tableName) {
            if (Schema::hasColumn($tableName, 'legacy_source_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropIndex(['legacy_source_id']);
                    $table->dropColumn('legacy_source_id');
                });
            }
        }
    }
};
