<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\MateriController;

// ================= LANDING PAGE =================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ================= AUTHENTICATION =================

/**
 * PERBAIKAN: Jangan arahkan ke view('auth.login').
 * Kita arahkan kembali ke 'welcome' dengan membawa session 'openLogin'.
 * Ini akan memicu JavaScript di app.blade.php untuk membuka Modal Login.
 */
Route::get('/login', function () {
    return redirect()->route('welcome')->with('openLogin', true);
})->name('login');

// Proses autentikasi (POST)
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= PROTECTED ROUTES (Hanya User Login) =================
Route::middleware(['auth'])->group(function () {
    
    // --- AKSES BERSAMA ---
    
    // 1. Halaman Pilih Mood
    Route::get('/mood', [MoodController::class, 'index'])->name('mood');

    // 2. Proses Simpan Mood
    Route::post('/mood/store', [MoodController::class, 'store'])->name('mood.store');

    // 3. Dashboard Utama (Siswa/Guru)
    Route::get('/dashboard', function () {
        // Cek mood khusus untuk Siswa (Guru tidak wajib pilih mood)
        if (Auth::user()->role == 'siswa' && !session()->has('current_mood')) {
            return redirect()->route('mood');
        }
        return view('dashboard');
    })->name('dashboard');

    // --- AKSES KHUSUS SISWA (Role Middleware) ---
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
        Route::get('/quiz', function() { 
            return view('quiz'); 
        })->name('quiz.index');
    });

    // --- AKSES KHUSUS GURU (Role Middleware) ---
    Route::middleware(['role:guru'])->group(function () {
        // Kelola Materi
        Route::get('/materi/create', [MateriController::class, 'create'])->name('materi.create');
        Route::post('/materi/store', [MateriController::class, 'store'])->name('materi.store');
        
        // Kelola Quiz
        Route::get('/quiz/create', function() { 
            return view('quiz_create'); 
        })->name('quiz.create');
    });

});