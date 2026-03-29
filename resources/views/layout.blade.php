<!DOCTYPE html>

<html>
<head>
    <title>Mood Learning</title>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>

<!-- ================= VANTA BACKGROUND ================= -->

<div id="vanta-bg">
<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-dark bg-dark bg-opacity-25 backdrop-blur fixed-top">
    <div class="container">
        <span class="navbar-brand fw-bold">Mood Learning</span>

        <div>
            <button id="btnLogin" class="btn btn-custom me-2">Login</button>
            <button id="btnRegister" class="btn btn-custom">Daftar</button>
        </div>
    </div>
</nav>

<!-- ================= CONTENT ================= -->
<div class="container pt-5" style="padding-top:100px;">
    @yield('content')
</div>

<!-- ================= FOOTER ================= -->
<footer class="footer-full">
    <div class="container-fluid">
        <div class="footer-content d-flex justify-content-between align-items-center flex-wrap">

            <!-- LEFT -->
            <div class="footer-left">
                © 2026 Mood Learning - Fajar Triadi
                <a href="https://github.com/fajartriadi24" target="_blank"> | Github</a>
                <a href="mailto:fajartriadi244@gmail.com"> | Email</a>
            </div>

            <!-- RIGHT -->
            <div class="footer-right">
                <a href="#"> | Facebook</a>
                <a href="#"> | Instagram</a>
            </div>

        </div>
    </div>
</footer>

</div>
<!-- ================= END VANTA ================= -->

<!-- ================= LOGIN MODAL ================= -->

<div id="authModal" class="auth-modal">
    <div class="auth-box glass-box">
    <button class="close-btn" id="closeLogin">✕</button>

    <h4 class="mb-3">Login</h4>

    <form action="/mood" method="GET">
        <input type="email" class="form-control mb-2" placeholder="Masukkan email" required>
        <input type="password" class="form-control mb-2" placeholder="Masukkan password" required>

        <div class="text-end mb-3">
            <a href="#" class="small-text">Lupa password?</a>
        </div>

        <button type="submit" class="btn btn-custom w-100 mb-3">Login</button>
    </form>

    <p class="small-text">
        Belum punya akun?
        <a href="#" id="toRegister">Daftar</a>
    </p>

</div>

</div>

<!-- ================= REGISTER MODAL ================= -->

<div id="registerModal" class="auth-modal">
    <div class="auth-box glass-box">

    <button class="close-btn" id="closeRegister">✕</button>

    <h4 class="mb-3">Daftar</h4>

    <input type="text" class="form-control mb-2" placeholder="Nama">
    <input type="email" class="form-control mb-2" placeholder="Email">
    <input type="password" class="form-control mb-3" placeholder="Password">

    <button class="btn btn-custom w-100 mb-3">Daftar</button>

    <p class="small-text">
        Sudah punya akun?
        <a href="#" id="toLogin">Login</a>
    </p>

</div>

</div>

<!-- ================= VANTA ================= -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.halo.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ================= VANTA =================
    VANTA.HALO({
        el: "#vanta-bg",
        mouseControls: true,
        touchControls: true,
        baseColor: 0x1a1a1a,
        backgroundColor: 0x000000,
        amplitudeFactor: 2.5,
        size: 1.8
    });

    // ================= ELEMENT =================
    const loginBtn = document.getElementById("btnLogin");
    const registerBtn = document.getElementById("btnRegister");

    const loginModal = document.getElementById("authModal");
    const registerModal = document.getElementById("registerModal");

    const closeLogin = document.getElementById("closeLogin");
    const closeRegister = document.getElementById("closeRegister");

    const toRegister = document.getElementById("toRegister");
    const toLogin = document.getElementById("toLogin");

    // ================= FUNCTION =================
    function openModal(modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }

    function closeModal(modal) {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    // ================= OPEN =================
    loginBtn.onclick = () => openModal(loginModal);
    registerBtn.onclick = () => openModal(registerModal);

    // ================= CLOSE =================
    closeLogin.onclick = () => closeModal(loginModal);
    closeRegister.onclick = () => closeModal(registerModal);

    // ================= SWITCH =================
    toRegister.onclick = (e) => {
        e.preventDefault();
        closeModal(loginModal);
        openModal(registerModal);
    };

    toLogin.onclick = (e) => {
        e.preventDefault();
        closeModal(registerModal);
        openModal(loginModal);
    };

    // ================= CLICK OUTSIDE =================
    window.onclick = function(e) {
        if (e.target === loginModal) closeModal(loginModal);
        if (e.target === registerModal) closeModal(registerModal);
    };

    // ================= ESC CLOSE =================
    document.addEventListener("keydown", function(e){
        if(e.key === "Escape"){
            closeModal(loginModal);
            closeModal(registerModal);
        }
    });

});
</script>

</body>
</html>
