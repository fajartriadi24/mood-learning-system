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
     * Proses Pendaftaran Akun Baru (Siswa & Guru)
     */
    public function register(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:4',
            'role' => 'required|in:siswa,guru', // Memastikan role valid
        ]);

        // 2. Logika Khusus Pendaftaran Guru (Wajib Kode 12)
        if ($request->role == 'guru') {
            if ($request->kode_guru !== '12') {
                return back()->with('error', 'Kode Guru salah! Anda tidak diizinkan mendaftar sebagai pengajar.');
            }
        }

        // 3. Membuat user baru ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Menyimpan role
            'kode_guru' => $request->kode_guru, // Menyimpan kode guru (bisa kosong untuk siswa)
        ]);

        // 4. Langsung login setelah daftar
        Auth::login($user);

        // 5. Redirect berdasarkan Role
        if ($user->role == 'guru') {
            return redirect()->route('dashboard')->with('success', 'Selamat datang, Guru!');
        }

        return redirect()->route('mood'); // Siswa wajib pilih mood dulu
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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan Role setelah login
            if ($user->role == 'guru') {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/mood');
        }

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