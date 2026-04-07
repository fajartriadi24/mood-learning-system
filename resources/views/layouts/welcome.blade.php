@extends('layouts.app')

@section('content')
<div class="hero-section text-center">
    <div class="glass-box hero-box mx-auto">
        <h1 class="fw-bold">Halo,<br> Anak-Anak</h1>
        <p class="desc opacity-75">
            Sistem pembelajaran adaptif pemrograman dasar yang menyesuaikan materi berdasarkan mood belajar kamu.
        </p>
        <button id="btnStart" class="btn btn-custom mt-3 px-5 py-2">
            Pelajari!
        </button>
    </div>
</div>

<div id="section2" class="container py-5 mt-5">
    <div class="glass-box p-5">
        <h3 class="text-center mb-5 fw-bold">Cara Kerja Sistem</h3>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="step-box p-4 h-100">
                    <div class="step-number mb-3">1</div>
                    <h5 class="fw-bold">Login</h5>
                    <p class="small opacity-75">Masuk ke sistem menggunakan akun siswa kamu.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="step-box p-4 h-100">
                    <div class="step-number mb-3">2</div>
                    <h5 class="fw-bold">Pilih Mood</h5>
                    <p class="small opacity-75">Beri tahu sistem bagaimana perasaanmu saat ini.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="step-box p-4 h-100">
                    <div class="step-number mb-3">3</div>
                    <h5 class="fw-bold">Rekomendasi</h5>
                    <p class="small opacity-75">Dapatkan materi yang paling cocok dengan kondisi belajarmu.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Smooth Scroll ke Section 2
    document.getElementById("btnStart").onclick = function () {
        document.getElementById("section2").scrollIntoView({
            behavior: "smooth"
        });
    };
</script>
@endsection