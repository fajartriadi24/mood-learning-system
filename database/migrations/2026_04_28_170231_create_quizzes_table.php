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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            // Menghubungkan kuis ke materi tertentu
            $table->foreignId('materi_id')->constrained('materis')->onDelete('cascade');
            
            // Kolom untuk menampung data soal
            $table->text('question_text'); // Teks Pertanyaan
            $table->string('option_a');    // Pilihan A
            $table->string('option_b');    // Pilihan B
            $table->string('option_c');    // Pilihan C
            $table->string('option_d');    // Pilihan D
            $table->char('correct_answer', 1); // Kunci jawaban (a, b, c, atau d)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};