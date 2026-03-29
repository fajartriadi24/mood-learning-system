@extends('layout')

@section('content')

<!-- ================= HERO ================= -->
<div class="hero-section text-center">

    <div class="glass-box hero-box">

        <h1>Halo,<br> Anak-Anak</h1>

        <p class="desc">
            Sistem pembelajaran adaptif pemrograman dasar yang menyesuaikan materi berdasarkan mood belajar kamu.
        </p>

        <button id="btnStart" class="btn btn-custom mt-3">
            Pelajari!
        </button>

    </div>

</div>

<!-- ================= SECTION 2 ================= -->
<div id="section2" class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-10">

            <div class="glass-box">

                <h3 class="text-center mb-5">Cara Kerja Sistem</h3>

                <div class="row text-center">

                    <!-- STEP 1 -->
                    <div class="col-md-4 mb-4">
                        <div class="step-box">
                            <div class="step-number">1</div>
                            <h5>Login</h5>
                            <p>Masuk ke sistem menggunakan akun</p>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="col-md-4 mb-4">
                        <div class="step-box">

                            <div class="step-number">2</div>
                            <h5>Pilih Mood</h5>

                            <div class="mood-wrapper mt-3 mb-3">

                                <div class="mood-item">
                                    <button class="mood-btn" data-mood="semangat">
                                        <span>😊</span>
                                    </button>
                                    <div class="mood-label">Semangat</div>
                                </div>

                                <div class="mood-item">
                                    <button class="mood-btn" data-mood="biasa">
                                        <span>😐</span>
                                    </button>
                                    <div class="mood-label">Biasa</div>
                                </div>

                                <div class="mood-item">
                                    <button class="mood-btn" data-mood="lelah">
                                        <span>😴</span>
                                    </button>
                                    <div class="mood-label">Lelah</div>
                                </div>

                                <div class="mood-item">
                                    <button class="mood-btn" data-mood="bingung">
                                        <span>😕</span>
                                    </button>
                                    <div class="mood-label">Bingung</div>
                                </div>

                            </div>

                            <div id="hasilMood" class="hasil-text">
                                Pilih mood untuk melihat rekomendasi
                            </div>

                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="col-md-4 mb-4">
                        <div class="step-box">
                            <div class="step-number">3</div>
                            <h5>Rekomendasi</h5>
                            <p>Materi disesuaikan dengan kondisi belajar</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ================= SCRIPT ================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    // SCROLL
    document.getElementById("btnStart").onclick = function () {
        document.getElementById("section2").scrollIntoView({
            behavior: "smooth"
        });
    };

    const hasil = document.getElementById("hasilMood");

    document.querySelectorAll(".mood-btn").forEach(btn => {
        btn.addEventListener("click", function () {

            // reset semua
            document.querySelectorAll(".mood-btn").forEach(b => b.classList.remove("active"));
            document.querySelectorAll(".mood-item").forEach(i => i.classList.remove("active"));

            // aktifkan
            this.classList.add("active");
            this.parentElement.classList.add("active");

            const mood = this.dataset.mood;
            let text = "";

            if (mood === "semangat") {
                text = "🔥 Latihan soal dengan tingkat kesulitan tinggi";
            } else if (mood === "biasa") {
                text = "📘 Materi pembelajaran standar + kuis";
            } else if (mood === "lelah") {
                text = "🎥 Video pembelajaran singkat";
            } else if (mood === "bingung") {
                text = "🧠 Penjelasan ulang konsep dasar";
            }

            hasil.innerHTML = "<b>Rekomendasi:</b><br>" + text;

        });
    });

});
</script>

@endsection