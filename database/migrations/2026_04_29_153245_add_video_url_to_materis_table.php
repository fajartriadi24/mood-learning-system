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
        Schema::table('materis', function (Blueprint $table) {
            // 1. Ubah video_path jadi nullable (boleh kosong kalau pakai YouTube)
            $table->string('video_path')->nullable()->change();
            
            // 2. Tambah kolom video_url untuk link YouTube
            $table->string('video_url')->nullable()->after('video_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            // Kembalikan ke semula jika di-rollback
            $table->string('video_path')->nullable(false)->change();
            $table->dropColumn('video_url');
        });
    }
};