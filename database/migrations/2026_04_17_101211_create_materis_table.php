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
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            // Menghubungkan materi dengan Guru yang upload
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            $table->string('judul');
            $table->string('mood_category'); // Kolom krusial untuk fitur adaptif mood kamu
            $table->string('video_path');    // Untuk menyimpan alamat file video di folder storage
            $table->string('quiz_link')->nullable(); // Opsional, bisa dikosongkan

            // PENJELASAN MATERI (Gunakan longText agar kuat menampung banyak gambar Base64)
            $table->longText('deskripsi')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};