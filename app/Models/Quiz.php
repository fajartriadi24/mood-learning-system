<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    // Supaya Laravel tahu kolom mana saja yang boleh diisi
    protected $fillable = [
        'materi_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation' // Tambahkan ini supaya pembahasan bisa disimpan
    ];

    // Hubungan balik ke Materi
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}