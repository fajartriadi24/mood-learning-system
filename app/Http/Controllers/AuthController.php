<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Proses Pendaftaran Akun Baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:4'
        ]);

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Tetap gunakan Hash::make untuk keamanan
        ]);

        // Langsung login setelah daftar
        Auth::login($user);

        // Redirect ke halaman pilihan mood
        return redirect()->route('mood.index');
    }

    /**
     * Proses Login Pengguna
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Menggunakan Auth::attempt (Cara standar Laravel)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect ke halaman pilihan mood
            return redirect()->intended('/mood');
        }

        // Jika gagal, kembalikan dengan pesan error
        throw ValidationException::withMessages([
            'email' => ['Email atau password yang kamu masukkan salah.'],
        ]);
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}