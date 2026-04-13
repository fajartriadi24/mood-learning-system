<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div id="main-wrapper" class="{{ Route::currentRouteName() }}">
    
    <nav class="navbar navbar-dark fixed-top navbar-stay">
        <div class="container">
            <span class="navbar-brand fw-bold" style="color: #c1ff72;">Mood Learning</span>
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

{{-- MODAL LOGIN --}}
<div id="authModal" class="auth-modal" style="display: none;">
    <div class="auth-box glass-box">
        <h4 class="mb-4 text-center fw-bold">Login</h4>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            
            <div class="password-container mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" id="loginPass" required>
                <i class="bi bi-eye toggle-password" onclick="togglePass('loginPass', this)"></i>
            </div>

            <button type="submit" class="btn btn-custom w-100 py-2">Masuk</button>
        </form>
        <p class="small-text mt-3 text-center">Belum punya akun? <a href="javascript:void(0)" id="toRegister" class="text-info">Daftar</a></p>
    </div>
</div>

{{-- MODAL REGISTER --}}
<div id="registerModal" class="auth-modal" style="display: none;">
    <div class="auth-box glass-box">
        <h4 class="mb-4 text-center fw-bold">Daftar Akun</h4>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="small text-white opacity-75 mb-2 d-block text-center">Daftar Sebagai:</label>
                <div class="d-flex gap-2">
                    <input type="radio" class="btn-check" name="role" id="roleSiswa" value="siswa" checked required onclick="toggleGuruCode(false)">
                    <label class="btn btn-outline-custom w-100 py-2 fw-bold" for="roleSiswa">Siswa</label>

                    <input type="radio" class="btn-check" name="role" id="roleGuru" value="guru" required onclick="toggleGuruCode(true)">
                    <label class="btn btn-outline-custom w-100 py-2 fw-bold" for="roleGuru">Guru</label>
                </div>
            </div>

            <div id="guruCodeWrapper" class="mb-2" style="display: none;">
                <input type="text" name="kode_guru" class="form-control guru-code-input" placeholder="Masukkan Kode Guru">
                <small class="text-white opacity-50" style="font-size: 10px;">*Wajib diisi Kode Guru</small>
            </div>

            <input type="text" name="name" class="form-control mb-2" placeholder="Nama Lengkap" required>
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            
            <div class="password-container mb-2">
                <input type="password" name="password" class="form-control" placeholder="Password" id="regPass" required>
                <i class="bi bi-eye toggle-password" onclick="togglePass('regPass', this)"></i>
            </div>

            <div class="password-container mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi Password" id="regPassConfirm" required>
                <i class="bi bi-eye toggle-password" onclick="togglePass('regPassConfirm', this)"></i>
            </div>
            
            <button type="submit" class="btn btn-custom w-100 py-2">Daftar Sekarang</button>
        </form>
        <p class="small-text mt-3 text-center">Sudah punya akun? <a href="javascript:void(0)" id="toLogin" class="text-info">Login</a></p>
    </div>
</div>

<script>
    // Fungsi Lihat Password
    function togglePass(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    function toggleGuruCode(show) {
        const wrapper = document.getElementById('guruCodeWrapper');
        wrapper.style.display = show ? 'block' : 'none';
        const input = wrapper.querySelector('input');
        if(!show) {
            input.value = ''; 
            input.removeAttribute('required');
        } else {
            input.setAttribute('required', 'required');
        }
    }

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