<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            // Siapa yang mengerjakan kuisnya
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Kuis dari materi mana yang dikerjakan
            $table->foreignId('materi_id')->constrained('materis')->onDelete('cascade');
            // Skor yang didapat siswa (0-100)
            $table->integer('score');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};