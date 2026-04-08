@extends('layouts.app')

@section('content')
{{-- Container justify-content-end agar kartu berada di sisi kanan --}}
<div class="d-flex justify-content-end align-items-center pe-md-5" style="min-height: 80vh;">
    
    {{-- Kartu dibuat Hitam Solid dengan Border tipis --}}
    <div class="glass-box text-center p-5 me-lg-5" style="width: 500px; max-width: 95%; border-radius: 35px; background: #000000 !important; border: 1px solid rgba(255,255,255,0.1) !important; box-shadow: 0 25px 50px rgba(0,0,0,0.5) !important;">
        
        <div class="mb-4">
            <div class="fs-1 mb-2 animate-bounce">👤</div>
            <h2 class="fw-bold text-white">Halo, {{ Auth::user()->name }}!</h2>
            <p class="text-white opacity-75 fw-medium">Bagaimana mood belajar kamu hari ini?</p>
        </div>

        <form action="{{ route('mood.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                {{-- Mood: Semangat --}}
                <div class="col-6">
                    <button type="submit" name="mood" value="semangat" class="btn mood-card-btn green-hover">
                        <span class="fs-2">😊</span><br>
                        <span class="fs-6 fw-bold d-block mt-2">Semangat</span>
                    </button>
                </div>
                {{-- Mood: Biasa --}}
                <div class="col-6">
                    <button type="submit" name="mood" value="biasa" class="btn mood-card-btn blue-hover">
                        <span class="fs-2">😐</span><br>
                        <span class="fs-6 fw-bold d-block mt-2">Biasa</span>
                    </button>
                </div>
                {{-- Mood: Lelah --}}
                <div class="col-6">
                    <button type="submit" name="mood" value="lelah" class="btn mood-card-btn yellow-hover">
                        <span class="fs-2">😴</span><br>
                        <span class="fs-6 fw-bold d-block mt-2">Lelah</span>
                    </button>
                </div>
                {{-- Mood: Bingung --}}
                <div class="col-6">
                    <button type="submit" name="mood" value="bingung" class="btn mood-card-btn red-hover">
                        <span class="fs-2">😕</span><br>
                        <span class="fs-6 fw-bold d-block mt-2">Bingung</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Tombol dibuat Putih Solid */
    .mood-card-btn {
        background: #ffffff !important;
        border: none;
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        color: #000000 !important; /* Teks di dalam tombol putih jadi hitam */
        width: 100%;
        padding: 25px 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Efek Hover Tetap Dipertahankan (Warna-warni) */
    .green-hover:hover { 
        background: #63c788 !important; /* Berubah jadi hijau solid saat hover */
        color: #ffffff !important;
        box-shadow: 0 10px 25px rgba(74, 222, 128, 0.5); 
        transform: translateY(-8px);
    }
    .blue-hover:hover { 
        background: #93c3ff !important; /* Berubah jadi biru solid saat hover */
        color: #ffffff !important;
        box-shadow: 0 10px 25px rgba(96, 165, 250, 0.5); 
        transform: translateY(-8px);
    }
    .yellow-hover:hover { 
        background: #ffe57f !important; /* Berubah jadi kuning solid saat hover */
        color: #ffffff !important;
        box-shadow: 0 10px 25px rgba(250, 204, 21, 0.5); 
        transform: translateY(-8px);
    }
    .red-hover:hover { 
        background: #ff9b9b !important; /* Berubah jadi merah solid saat hover */
        color: #ffffff !important;
        box-shadow: 0 10px 25px rgba(248, 113, 113, 0.5); 
        transform: translateY(-8px);
    }
    
    .animate-bounce {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(-8px); }
        50% { transform: translateY(0); }
    }
</style>
@endsection