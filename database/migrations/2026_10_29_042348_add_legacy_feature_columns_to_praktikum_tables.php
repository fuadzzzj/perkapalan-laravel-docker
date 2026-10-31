<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('jadwal_praktikum', 'mata_pelajaran')) {
            Schema::table('jadwal_praktikum', function (Blueprint $table) {
                $table->string('mata_pelajaran')->after('id');
                $table->date('tanggal')->after('mata_pelajaran');
                $table->time('jam')->after('tanggal');
                $table->string('ruang')->after('jam');
                $table->string('foto_path')->nullable()->after('ruang');
                $table->foreignId('dibuat_oleh')->after('foto_path')->constrained('users')->restrictOnDelete();
            });
        }

        if (! Schema::hasColumn('pengumuman', 'judul')) {
            Schema::table('pengumuman', function (Blueprint $table) {
                $table->string('judul')->after('id');
                $table->text('isi')->after('judul');
                $table->foreignId('dibuat_oleh')->after('isi')->constrained('users')->restrictOnDelete();
            });
        }

        if (! Schema::hasColumn('chat', 'user_id')) {
            Schema::table('chat', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->restrictOnDelete();
                $table->text('pesan')->nullable()->after('user_id');
                $table->string('media_path')->nullable()->after('pesan');
                $table->enum('media_type', ['image', 'video', 'audio', 'document'])->nullable()->after('media_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('chat', 'media_type')) {
            Schema::table('chat', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn(['user_id', 'pesan', 'media_path', 'media_type']);
            });
        }

        if (Schema::hasColumn('pengumuman', 'judul')) {
            Schema::table('pengumuman', function (Blueprint $table) {
                $table->dropForeign(['dibuat_oleh']);
                $table->dropColumn(['judul', 'isi', 'dibuat_oleh']);
            });
        }

        if (Schema::hasColumn('jadwal_praktikum', 'mata_pelajaran')) {
            Schema::table('jadwal_praktikum', function (Blueprint $table) {
                $table->dropForeign(['dibuat_oleh']);
                $table->dropColumn(['mata_pelajaran', 'tanggal', 'jam', 'ruang', 'foto_path', 'dibuat_oleh']);
            });
        }
    }
};
