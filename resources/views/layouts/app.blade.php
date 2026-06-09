<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

{{-- Wrapper utama dengan perbaikan background dinamis --}}
<div id="main-wrapper" class="{{ (Request::is('materi*') || Request::is('quiz*') || Request::is('dashboard') || Request::is('history*')) ? 'dashboard' : Route::currentRouteName() }}">
    
    <nav class="navbar navbar-expand-lg fixed-top navbar-stay">
        <div class="container">
            {{-- LOGO --}}
            <a class="navbar-brand fw-bold" href="{{ url('/') }}" style="color: #639b18; font-size: 1.5rem;">
                Emotikode
            </a>

            {{-- MENU NAVIGASI (Hanya muncul di halaman Welcome) --}}
            @if(Request::is('/'))
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-4 me-auto mb-2 mb-lg-0">
                    {{-- MOOD-TECH --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link fw-semibold px-3" href="#" role="button" data-bs-toggle="dropdown">
                            Mood-Tech <i class="bi bi-chevron-down ms-1 chevron-green"></i>
                        </a>
                        <div class="dropdown-menu shadow-lg border-0 p-4 mt-3 rounded-4 animated-fade-in" style="width: 300px;">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3 text-success"><i class="bi bi-cpu"></i></div>
                                <div>
                                    <h6 class="fw-bold m-0 small">Algoritma Cerdas</h6>
                                    <p class="text-muted m-0" style="font-size: 11px;">Penyesuaian materi otomatis berdasarkan kondisi emosional.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary"><i class="bi bi-brain"></i></div>
                                <div>
                                    <h6 class="fw-bold m-0 small">Metode Riset</h6>
                                    <p class="text-muted m-0" style="font-size: 11px;">Berdasarkan efektivitas pembelajaran di Lampung.</p>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- MATERI POPULER --}}
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3" href="#materi">
                            Materi Populer <i class="bi bi-chevron-down ms-1 chevron-green"></i>
                        </a>
                    </li>

                    {{-- KATA SISWA --}}
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3" href="#testimoni">
                            Kata Siswa <i class="bi bi-chevron-down ms-1 chevron-green"></i>
                        </a>
                    </li>
                </ul>
            @else
            <div class="ms-auto">
            @endif

                <div class="d-flex align-items-center gap-2">
                    @auth
                        {{-- TOMBOL PROFIL DI SAMPING KIRI HALO NAMA --}}
                        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; border: 1px solid rgba(0,0,0,0.1);" title="Pengaturan Profil">
                            <i class="bi bi-person-fill text-dark"></i>
                        </a>
                        <span class="text-dark small me-3 d-none d-md-inline">Halo, {{ Auth::user()->name }}</span>
                        
                        <a href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="btn btn-custom btn-sm px-4 rounded-pill">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    @else
                        <button type="button" id="btnLogin" class="btn btn-custom btn-sm px-4 rounded-pill">Login</button>
                        <button type="button" id="btnRegister" class="btn btn-custom btn-sm px-4 rounded-pill">Daftar</button>
                    @endauth
                </div>
            </div> {{-- End div ms-auto atau collapse --}}
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
    <div class="auth-box logindaftar-box">
        <h4 class="mb-4 text-center fw-bold">Login</h4>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            
            <div class="password-container mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" id="loginPass" required>
                <i class="bi bi-eye toggle-password" onclick="togglePass('loginPass', this)"></i>
            </div>

            <button type="submit" class="btn btn-masuk w-100 py-2">Masuk</button>
        </form>
        <p class="small-text mt-3 text-center">Belum punya akun? <a href="javascript:void(0)" id="toRegister" class="text-info">Daftar</a></p>
    </div>
</div>

{{-- MODAL REGISTER --}}
<div id="registerModal" class="auth-modal" style="display: none;">
     <div class="auth-box logindaftar-box">
        <h4 class="mb-4 text-center fw-bold">Daftar Akun</h4>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="small text-black opacity-75 mb-2 d-block text-center">Daftar Sebagai:</label>
                <div class="d-flex gap-2">
                    <input type="radio" class="btn-check" name="role" id="roleSiswa" value="siswa" checked required onclick="toggleGuruCode(false)">
                    <label class="btn btn-outline-custom w-100 py-2 fw-bold" for="roleSiswa">Siswa</label>

                    <input type="radio" class="btn-check" name="role" id="roleGuru" value="guru" required onclick="toggleGuruCode(true)">
                    <label class="btn btn-outline-custom w-100 py-2 fw-bold" for="roleGuru">Guru</label>
                </div>
            </div>

            <div id="guruCodeWrapper" class="mb-2" style="display: none;">
                <input type="text" name="kode_guru" class="form-control guru-code-input" placeholder="Masukkan Kode Guru">
                <small class="text-black opacity-50" style="font-size: 10px;">*Wajib diisi Kode Guru</small>
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
            
            <button  type="submit" class="btn btn-masuk w-100 py-2">Daftar Sekarang</button>
        </form>
        <p class="small-text mt-3 text-center">Sudah punya akun? <a href="javascript:void(0)" id="toLogin" class="text-info">Login</a></p>
    </div>
</div>

<script>
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