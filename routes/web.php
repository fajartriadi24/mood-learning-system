<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoodController;

// ================= LANDING PAGE =================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ================= AUTHENTICATION =================
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= PROTECTED ROUTES =================
Route::middleware(['auth'])->group(function () {
    
    // Halaman Pilih Mood (Nama disederhanakan jadi 'mood')
    Route::get('/mood', [MoodController::class, 'index'])->name('mood');

    // Proses Simpan Mood
    Route::post('/mood/store', [MoodController::class, 'store'])->name('mood.store');

    // Dashboard Utama
    Route::get('/dashboard', function () {
        // Jika belum pilih mood, balikkan ke rute 'mood' (Bukan mood.index)
        if (!session()->has('current_mood')) {
            return redirect()->route('mood');
        }
        return view('dashboard');
    })->name('dashboard');

});