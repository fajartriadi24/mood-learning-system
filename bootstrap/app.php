<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Mendaftarkan Alias Middleware untuk Role Based Access Control
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 2. Mengatur kemana user dilempar (Redirect)
        $middleware->redirectTo(
            guests: '/',        // Jika belum login dan coba akses halaman terproteksi, balik ke Welcome
            users: '/dashboard', // Jika sudah login tapi coba buka halaman login lagi, lempar ke Dashboard
        );

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();