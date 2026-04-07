<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

{{-- ID main-wrapper dengan class dinamis --}}
<div id="main-wrapper" class="{{ Route::currentRouteName() }}">
    
    <nav class="navbar navbar-dark fixed-top navbar-stay">
        <div class="container">
            <span class="navbar-brand fw-bold">Mood Learning</span>
            <div class="d-flex align-items-center">
                @auth
                    <span class="text-white me-3 small d-none d-md-inline">Halo, {{ Auth::user()->name }}</span>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="btn btn-custom btn-sm">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                @else
                    <button type="button" id="btnLogin" class="btn btn-custom me-2">Login</button>
                    <button type="button" id="btnRegister" class="btn btn-custom">Daftar</button>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container" style="padding-top:120px; min-height: 100vh;">
        @yield('content')
    </div>

    @if(Request::is('/'))
    <footer class="footer-full">
        <div class="container text-center">
            <p class="small opacity-50 m-0">© 2026 Mood Learning - Fajar Triadi | Web Framework</p>
        </div>
    </footer>
    @endif
</div>

<div id="authModal" class="auth-modal" style="display: none;">
    <div class="auth-box glass-box">
        <h4 class="mb-4 text-center fw-bold">Login</h4>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-custom w-100 py-2">Masuk</button>
        </form>
        <p class="small-text mt-3 text-center">Belum punya akun? <a href="javascript:void(0)" id="toRegister" class="text-info">Daftar</a></p>
    </div>
</div>

<div id="registerModal" class="auth-modal" style="display: none;">
    <div class="auth-box glass-box">
        <h4 class="mb-4 text-center fw-bold">Daftar Akun</h4>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <input type="text" name="name" class="form-control mb-2" placeholder="Nama Lengkap" required>
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
            <input type="password" name="password_confirmation" class="form-control mb-3" placeholder="Ulangi Password" required>
            <button type="submit" class="btn btn-custom w-100 py-2">Daftar Sekarang</button>
        </form>
        <p class="small-text mt-3 text-center">Sudah punya akun? <a href="javascript:void(0)" id="toLogin" class="text-info">Login</a></p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const loginModal = document.getElementById("authModal");
    const regModal = document.getElementById("registerModal");

    function closeAllModals() {
        loginModal.style.display = "none";
        regModal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    document.getElementById("btnLogin")?.addEventListener("click", () => {
        loginModal.style.display = "flex";
        document.body.style.overflow = "hidden";
    });

    document.getElementById("btnRegister")?.addEventListener("click", () => {
        regModal.style.display = "flex";
        document.body.style.overflow = "hidden";
    });

    window.onclick = (e) => { if (e.target == loginModal || e.target == regModal) closeAllModals(); }
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeAllModals(); });

    document.getElementById("toRegister")?.addEventListener("click", () => { loginModal.style.display = "none"; regModal.style.display = "flex"; });
    document.getElementById("toLogin")?.addEventListener("click", () => { regModal.style.display = "none"; loginModal.style.display = "flex"; });
});
</script>
</body>
</html>