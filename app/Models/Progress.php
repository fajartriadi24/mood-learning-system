<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'progress';

    // IZIN MASS ASSIGNMENT: Ini yang bikin error tadi Jar
    protected $fillable = [
        'user_id', 
        'materi_id', 
        'video_finished', 
        'quiz_finished'
    ];

    // Relasi ke User (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Materi
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}