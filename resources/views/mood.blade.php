@extends('layouts.app')

@section('content')
{{-- Container diubah ke justify-content-end agar kartu geser ke kanan --}}
<div class="d-flex justify-content-end align-items-center pe-md-5" style="min-height: 80vh;">
    
    {{-- Glass box ditambahkan margin-right agar tidak terlalu nempel ke pinggir layar --}}
    <div class="glass-box text-center p-5 me-lg-5" style="width: 500px; max-width: 95%;">
        
        <div class="mb-4">
            <div class="fs-1 mb-2 animate-bounce">👤</div>
            <h2 class="fw-bold">Halo, {{ Auth::user()->name }}!</h2>
            <p class="opacity-75">Bagaimana mood belajar kamu hari ini?</p>
        </div>

        <form action="{{ route('mood.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-6">
                    <button type="submit" name="mood" value="semangat" class="btn btn-outline-light w-100 py-4 fs-3 mood-card-btn green-hover">
                        😊 <br> <span class="fs-6 fw-normal d-block mt-2">Semangat</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="submit" name="mood" value="biasa" class="btn btn-outline-light w-100 py-4 fs-3 mood-card-btn blue-hover">
                        😐 <br> <span class="fs-6 fw-normal d-block mt-2">Biasa</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="submit" name="mood" value="lelah" class="btn btn-outline-light w-100 py-4 fs-3 mood-card-btn yellow-hover">
                        😴 <br> <span class="fs-6 fw-normal d-block mt-2">Lelah</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="submit" name="mood" value="bingung" class="btn btn-outline-light w-100 py-4 fs-3 mood-card-btn red-hover">
                        😕 <br> <span class="fs-6 fw-normal d-block mt-2">Bingung</span>
                    </button>
                </div>
            </div>
        </form>
        
    </div>
</div>

<style>
    /* Tambahan style khusus untuk tombol mood agar lebih estetik */
    .mood-card-btn {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        transition: all 0.3s ease;
        color: white;
    }

    .mood-card-btn:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.15);
        color: white;
    }

    /* Efek warna per mood */
    .green-hover:hover { border-color: #4ade80 !important; box-shadow: 0 0 20px rgba(74, 222, 128, 0.2); }
    .blue-hover:hover { border-color: #60a5fa !important; box-shadow: 0 0 20px rgba(96, 165, 250, 0.2); }
    .yellow-hover:hover { border-color: #facc15 !important; box-shadow: 0 0 20px rgba(250, 204, 21, 0.2); }
    .red-hover:hover { border-color: #f87171 !important; box-shadow: 0 0 20px rgba(248, 113, 113, 0.2); }
    
    .animate-bounce {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(-5%); }
        50% { transform: translateY(0); }
    }

    /* Memperkuat blur agar konten tetap terbaca di atas background ilustrasi */
    .glass-box {
        backdrop-filter: blur(25px) !important;
        background: rgba(0, 0, 0, 0.3) !important;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bg = document.getElementById('vanta-bg');
        if(bg) {
            bg.style.backgroundImage = "url('{{ asset('images/mbg.png') }}')"; 
            bg.style.backgroundSize = "cover";
            bg.style.backgroundPosition = "center";
            bg.style.backgroundAttachment = "fixed";
            bg.style.backgroundRepeat = "no-repeat";
        }
    });
</script>
@endsection