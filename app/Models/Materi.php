<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'mood_category',
        'video_path',
        'video_url', // Tambahkan ini agar database mau menyimpan link
        'deskripsi',
    ];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}