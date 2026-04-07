<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mood; 
use Illuminate\Support\Facades\Auth;

class MoodController extends Controller
{
    /**
     * Tampilkan halaman pilihan mood
     */
    public function index()
    {
        return view('mood');
    }

    /**
     * Simpan mood yang dipilih ke Database dan Session
     */
    public function store(Request $request)
    {
        // 1. Validasi agar input tidak kosong atau aneh-aneh
        $request->validate([
            'mood' => 'required|in:semangat,biasa,lelah,bingung'
        ]);

        // 2. Simpan ke Database dengan menyertakan ID user yang sedang login
        Mood::create([
            'user_id' => Auth::id(), // Mengambil ID Fajar atau siswa yang login
            'mood'    => $request->mood,
        ]);

        // 3. Simpan ke Session agar Dashboard bisa langsung berubah tanpa query ulang
        session(['current_mood' => $request->mood]);

        // 4. Arahkan ke Dashboard materi
        return redirect()->route('dashboard');
    }
}