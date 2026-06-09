<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login sama sekali
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Ambil role user & bersihkan dari spasi/huruf kapital (Biar Sinkron sama Database)
        $userRole = trim(strtolower(Auth::user()->role));
        $requiredRole = trim(strtolower($role));

        // 3. Bandingkan role
        if ($userRole !== $requiredRole) {
            // Jika role tidak cocok, paksa logout atau lempar ke welcome
            // Tambahkan pesan error agar kamu tahu dia ditendang di sini
            return redirect('/')->with('error', "Akses Ditolak! Role Anda: $userRole, Butuh: $requiredRole");
        }

        return $next($request);
    }
}