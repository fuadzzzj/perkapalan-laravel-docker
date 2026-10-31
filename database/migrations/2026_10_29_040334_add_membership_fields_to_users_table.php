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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('username')->nullable()->unique()->after('id');
            $table->string('nama_lengkap')->nullable()->after('username');
            $table->string('kelas')->nullable()->after('nama_lengkap');
            $table->string('nis')->nullable()->unique()->after('kelas');
            $table->string('jurusan')->nullable()->after('nis');
            $table->unsignedSmallInteger('tahun_masuk')->nullable()->after('jurusan');
            $table->enum('role', ['admin', 'guru', 'anggota'])->default('anggota')->after('password');
            $table->enum('membership_status', ['pending', 'approved', 'rejected'])->default('approved')->after('role');
            $table->timestamp('approved_at')->nullable()->after('membership_status');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropUnique(['username']);
            $table->dropUnique(['nis']);
            $table->dropColumn([
                'username', 'nama_lengkap', 'kelas', 'nis', 'jurusan', 'tahun_masuk',
                'role', 'membership_status', 'approved_at', 'approved_by',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
};
