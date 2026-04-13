<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    /**
     * Menampilkan halaman materi pembelajaran adaptif.
     * Fungsi ini mengambil data mood dari session untuk menentukan konten.
     */
    public function index()
    {
        // 1. Ambil mood dari session yang diset oleh MoodController.
        // Jika session kosong (misal user langsung ketik URL), default ke 'biasa'.
        $mood = session('current_mood', 'biasa');

        // 2. Kirim data ke view 'materi.blade.php'
        return view('materi', [
            'mood' => $mood,
            'userName' => Auth::user()->name
        ]);
    }

    /**
     * Fitur tambahan: Menandai materi sebagai selesai (Optional untuk Skripsi)
     */
    public function finishMateri(Request $request)
    {
        // Di sini kamu bisa menambahkan logika simpan progres ke database nanti
        return redirect()->route('dashboard')->with('success', 'Materi berhasil diselesaikan!');
    }
}