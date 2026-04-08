@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Header Materi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Pemrograman Dasar: Variabel & Tipe Data</h2>
            <p class="text-muted m-0">Halo, <strong>{{ Auth::user()->name }}</strong>! Kamu belajar dalam mode: 
                <span class="badge bg-primary px-3 py-2 ms-2">
                    {{ ucfirst($mood ?? 'Biasa') }}
                </span>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-dark btn-sm shadow-sm px-3">Ke Dashboard</a>
            <a href="{{ route('mood') }}" class="btn btn-glass-outline btn-sm shadow-sm">Ganti Mood</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- AREA KONTEN ADAPTIF --}}
            <div class="glass-box p-5 mb-4 shadow-sm" style="min-height: 450px; background: rgba(255,255,255,0.95) !important; border: 1px solid rgba(0,0,0,0.1) !important;">
                
                @if($mood == 'semangat')
                    {{-- MODE SEMANGAT: TANTANGAN TINGGI --}}
                    <div class="animate-fadeIn">
                        <h3 class="fw-bold text-success mb-3">🚀 Tantangan Algoritma (Level Hard)</h3>
                        <p class="text-dark fs-5">Kamu sedang bersemangat! Mari pecahkan kasus penukaran nilai variabel tanpa menggunakan variabel bantuan.</p>
                        <div class="bg-dark p-4 rounded-4 text-light my-4 shadow-lg">
                            <pre class="m-0"><code>// Tantangan Logika:
// Input: a = 5, b = 10
// Proses: (Hanya gunakan operasi +, -)
// Output: a = 10, b = 5</code></pre>
                        </div>
                        <p class="text-dark fw-medium">Tuliskan alur penyelesaiannya di buku catatanmu sebelum lanjut ke kuis!</p>
                    </div>

                @elseif($mood == 'lelah')
                    {{-- MODE LELAH: VIDEO PEMBELAJARAN --}}
                    <div class="animate-fadeIn">
                        <h3 class="fw-bold text-warning mb-3">🎥 Materi Video (Mode Santai)</h3>
                        <p class="text-dark fs-5">Jangan terlalu memaksakan diri. Tonton video konsep dasar variabel ini agar tetap produktif meski sedang lelah.</p>
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg my-4">
                            <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Variabel Dasar" allowfullscreen></iframe>
                        </div>
                        <p class="text-muted small">*Video berdurasi 5 menit. Sediakan camilan agar belajar lebih rileks.</p>
                    </div>

                @elseif($mood == 'bingung')
                    {{-- MODE BINGUNG: ANALOGI SEDERHANA --}}
                    <div class="animate-fadeIn">
                        <h3 class="fw-bold text-danger mb-3">💡 Analogi Sederhana (Konsep Gelas)</h3>
                        <p class="text-dark fs-5">Masih bingung? Bayangkan <strong>Variabel</strong> adalah sebuah <strong>Gelas</strong> bermerek.</p>
                        <div class="row align-items-center my-4 py-3 bg-light rounded-4">
                            <div class="col-md-4 text-center">
                                <span style="font-size: 100px;">🥛</span>
                            </div>
                            <div class="col-md-8">
                                <h5 class="fw-bold">Ingat Konsep Ini:</h5>
                                <ul class="text-dark">
                                    <li><strong>Variabel:</strong> Gelasnya (Wadah).</li>
                                    <li><strong>Data:</strong> Air isinya (Nilai).</li>
                                    <li><strong>Tipe Data:</strong> Jenis minumannya (Kopi, Jus, atau Air Putih).</li>
                                </ul>
                            </div>
                        </div>
                        <p class="text-dark fw-medium">Gelas jus tidak boleh diisi nasi. Begitupun variabel angka tidak boleh diisi teks!</p>
                    </div>

                @else
                    {{-- MODE BIASA: MODUL STANDAR --}}
                    <div class="animate-fadeIn">
                        <h3 class="fw-bold text-primary mb-3">📖 Modul: Apa itu Variabel?</h3>
                        <p class="text-dark fs-5">Variabel adalah sebuah nama yang mewakili suatu lokasi di memori komputer yang digunakan untuk menyimpan data.</p>
                        <div class="p-4 border-start border-primary border-4 bg-light my-4">
                            <h6 class="fw-bold">Contoh Penulisan:</h6>
                            <code>int angka = 10;</code><br>
                            <code>string nama = "Fajar Triadi";</code>
                        </div>
                        <p class="text-dark">Pelajari jenis-jenis tipe data seperti Integer, String, dan Boolean sebelum memulai kuis.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- SIDEBAR PROGRES & KUIS --}}
        <div class="col-lg-4">
            <div class="glass-box p-4 shadow-sm" style="background: rgba(255,255,255,0.9) !important; border: 1px solid rgba(0,0,0,0.1) !important;">
                <h5 class="fw-bold text-dark mb-4 text-center">Evaluasi Belajar</h5>
                
                <div class="card bg-dark text-white p-3 mb-4 rounded-4 border-0 shadow">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">📝</div>
                        <div>
                            <h6 class="m-0 fw-bold">Kuis Pemrograman</h6>
                            <small class="opacity-75">5 Soal Pilihan Ganda</small>
                        </div>
                    </div>
                    <button class="btn btn-light w-100 mt-3 fw-bold rounded-pill">Mulai Kuis</button>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-dark">Progress Materi</span>
                        <span class="small fw-bold text-dark">40%</span>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 10px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 40%"></div>
                    </div>
                </div>

                <hr class="my-4">
                
                <h6 class="fw-bold text-dark mb-3 small text-uppercase">Tips Belajar:</h6>
                <div class="alert alert-info py-2 small border-0 shadow-sm">
                    Jangan lupa istirahat tiap 25 menit (Teknik Pomodoro).
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fadeIn {
        animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .glass-box {
        border-radius: 30px;
        transition: 0.3s ease;
    }
</style>
@endsection