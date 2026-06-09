<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:4',
            'role' => 'required|in:siswa,guru',
        ]);

        if ($request->role == 'guru' && $request->kode_guru !== '12') {
            return back()->with('error', 'Kode Guru salah!');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kode_guru' => $request->kode_guru, 
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        Visit::create(['user_id' => $user->id]);

        return ($user->role == 'guru') ? redirect('/dashboard') : redirect('/mood');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // PAKSA REGENERATE
            $request->session()->regenerate();
            
            $user = Auth::user();
            Visit::create(['user_id' => $user->id]);

            // PAKSA KE PATH MENTAH
            if ($user->role == 'guru') {
                return redirect('/dashboard');
            }
            return redirect('/mood');
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}