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
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            
            // Menghubungkan ke tabel users (siswa)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Menghubungkan ke tabel materis (materi yang sedang dipelajari)
            // Pastikan nama tabel materi kamu adalah 'materis'
            $table->foreignId('materi_id')
                  ->constrained('materis')
                  ->onDelete('cascade');

            // Melacak apakah video sudah ditonton sampai habis (1 = Selesai, 0 = Belum)
            $table->boolean('video_finished')->default(false);

            // Melacak apakah kuis sudah dikerjakan (1 = Selesai, 0 = Belum)
            $table->boolean('quiz_finished')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};