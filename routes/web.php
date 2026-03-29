<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome')); // halaman awal

Route::get('/dashboard', fn() => view('dashboard'));
Route::get('/mood', fn() => view('mood'));
Route::get('/materi', fn() => view('materi'));
Route::get('/quiz', fn() => view('quiz'));
Route::get('/history', fn() => view('history'));
Route::get('/profile', fn() => view('profile'));