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
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= PROTECTED ROUTES (Hanya User Login) =================
Route::middleware(['auth'])->group(function () {
    
    // 1. Halaman Pilih Mood
    Route::get('/mood', [MoodController::class, 'index'])->name('mood');

    // 2. Proses Simpan Mood
    Route::post('/mood/store', [MoodController::class, 'store'])->name('mood.store');

    // 3. Dashboard Utama
    Route::get('/dashboard', function () {
        // Jika belum pilih mood, arahkan paksa ke halaman pilih mood
        if (!session()->has('current_mood')) {
            return redirect()->route('mood');
        }
        return view('dashboard');
    })->name('dashboard');

    // 4. Halaman Materi Pembelajaran (Adaptif)
    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');

});