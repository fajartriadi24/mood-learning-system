<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// ================= LANDING PAGE =================
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->intended('/dashboard');
    }
    return view('pages.welcome'); 
})->name('welcome');

// ================= AUTHENTICATION =================
Route::get('/login', function () {
    if (Auth::check()) return redirect()->intended('/dashboard');
    return redirect()->route('welcome')->with('openLogin', true);
})->name('login');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= PROTECTED ROUTES (Hanya User Login) =================
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/mood', function () {
        return view('pages.mood');
    })->name('mood');

    Route::post('/mood/store', [MoodController::class, 'store'])->name('mood.store');
    
    Route::get('/history', function () {
        return view('materi.history');
    })->name('history');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- AKSES MATERI ---
    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
    Route::post('/materi/{id}/video-done', [MateriController::class, 'markVideoDone'])->name('video.done');
    Route::post('/materi/{id}/quiz-done', [MateriController::class, 'markQuizDone'])->name('quiz.done');

    // --- AKSES KELOLA GURU (Tanpa Middleware Role) ---
    Route::get('/materi/create', [MateriController::class, 'create'])->name('materi.create');
    Route::post('/materi/store', [MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{id}/edit', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{id}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{id}', [MateriController::class, 'destroy'])->name('materi.destroy');
    
    Route::delete('/siswa/{id}', [DashboardController::class, 'destroySiswa'])->name('siswa.destroy');
    
    Route::get('/quiz/create', [MateriController::class, 'createQuiz'])->name('quiz.create');
    Route::post('/quiz/store', [MateriController::class, 'storeQuiz'])->name('quiz.store');
    Route::get('/quiz/{id}/edit', [MateriController::class, 'editQuiz'])->name('quiz.edit');
    Route::put('/quiz/{id}/update', [MateriController::class, 'updateQuiz'])->name('quiz.update');
    Route::delete('/quiz/{id}', [MateriController::class, 'destroyQuiz'])->name('quiz.destroy');

    Route::get('/quiz/{id}', [MateriController::class, 'showQuiz'])->name('quiz.show');
});

// JANGAN ADA BARIS 'require __DIR__.'/auth.php';' DI SINI!