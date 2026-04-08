<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    /**
     * Pastikan user sudah login sebelum mengakses materi
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman materi berdasarkan mood
     */
    public function index()
    {
        // 1. Ambil mood dari session yang diset oleh MoodController
        // Jika tidak ada, kita beri default 'biasa'
        $mood = session('current_mood', 'biasa');

        // 2. Kirim data mood ke view materi.blade.php
        return view('materi', [
            'mood' => $mood,
            'userName' => Auth::user()->name
        ]);
    }

    /**
     * Fitur tambahan: Menyelesaikan materi dan update progress
     */
    public function complete(Request $request)
    {
        // Di sini nanti kamu bisa tambahkan logika simpan progress ke database
        // sesuai alur sistem poin ke-6 yang kamu buat tadi.
        
        return redirect()->route('dashboard')->with('success', 'Selamat! Materi telah diselesaikan.');
    }
}