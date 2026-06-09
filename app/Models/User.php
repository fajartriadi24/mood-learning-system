<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // TAMBAHKAN INI: Agar role tersimpan ke database
        'kode_guru',   // TAMBAHKAN INI: Agar kode guru bisa disimpan
    ];

    /**
     * Relasi: Satu User (Guru) bisa memiliki banyak Materi
     */
    public function materis()
    {
        return $this->hasMany(Materi::class);
    }

    /**
     * Relasi: Satu User (Siswa) bisa memiliki banyak hasil kuis
     */
    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Relasi: Satu User (Siswa) memiliki data progres belajar
     */
    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}