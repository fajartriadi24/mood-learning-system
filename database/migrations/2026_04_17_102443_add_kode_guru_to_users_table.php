<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom kode_guru.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // nullable() digunakan agar siswa yang tidak punya kode tetap bisa daftar
            // after('role') digunakan agar posisi kolom di database berurutan setelah kolom role
            $table->string('kode_guru')->nullable()->after('role');
        });
    }

    /**
     * Balikkan migrasi (hapus kolom) jika terjadi kesalahan.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kode_guru');
        });
    }
};