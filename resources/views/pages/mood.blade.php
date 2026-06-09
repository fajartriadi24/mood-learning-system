@extends('layouts.app')

@section('content')
{{-- PERBAIKAN: justify-content-center agar kartu berada tepat di tengah --}}
<div class="d-flex justify-content-center align-items-center min-vh-100">
    
    {{-- Kartu Utama: Margin-top disesuaikan agar pas di tengah secara visual --}}
    <div class="glass-box text-center p-5 shadow-lg" 
         style="width: 500px; max-width: 95%; border-radius: 35px; background: #ffffff00 !important; border: 1px solid rgba(255,255,255,0.1) !important; overflow: hidden; margin-top: -50px;">
        
        <div class="mb-4">
            <div class="fs-1 mb-2 animate-bounce">👨🏻‍🏫</div>
            <h2 class="fw-bold text-black mb-2">Halo, {{ Auth::user()->name }}!</h2>
            <p class="text-black opacity-75 fw-medium">Bagaimana mood belajar kamu hari ini?</p>
        </div>

        <form action="{{ route('mood.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                {{-- Mood: Semangat --}}
                <div class="col-6">
                    <button type="submit" name="mood" value="semangat" class="btn mood-card-btn green-hover">
                        <span class="fs-2">😊</span><br>
                        <span class="fs-6 fw-bold d-block mt-2 text-white">Semangat</span>
                    </button>
                </div>
                {{-- Mood: Biasa --}}
                <div class="col-6">
                    <button type="submit" name="mood" value="biasa" class="btn mood-card-btn blue-hover">
                        <span class="fs-2">🙂</span><br>
                        <span class="fs-6 fw-bold d-block mt-2 text-white">Biasa</span>
                    </button>
                </div>
                {{-- Mood: Lelah --}}
                <div class="col-6">
                    <button type="submit" name="mood" value="lelah" class="btn mood-card-btn yellow-hover">
                        <span class="fs-2">😔</span><br>
                        <span class="fs-6 fw-bold d-block mt-2 text-white">Lelah</span>
                    </button>
                </div>
                {{-- Mood: Bingung --}}
                <div class="col-6">
                    <button type="submit" name="mood" value="bingung" class="btn mood-card-btn red-hover">
                        <span class="fs-2">😕</span><br>
                        <span class="fs-6 fw-bold d-block mt-2 text-white">Bingung</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Menghilangkan scrollbar secara paksa */
    html, body {
        overflow: hidden;
    }

    /* Tombol Pilihan Mood: Default Gelap */
    .mood-card-btn {
        background: #000000;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 25px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        width: 100%;
        padding: 25px 10px;
        text-decoration: none;
    }

    /* Efek Hover */
    .green-hover:hover { 
        background: #27c93f !important; 
        box-shadow: 0 10px 25px rgba(34, 197, 94, 0.4); 
        transform: translateY(-8px);
    }
    .blue-hover:hover { 
        background: #007bff !important; 
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4); 
        transform: translateY(-8px);
    }
    .yellow-hover:hover { 
        background: #ffc107 !important; 
        box-shadow: 0 10px 25px rgba(234, 179, 8, 0.4); 
        transform: translateY(-8px);
    }
    .red-hover:hover { 
        background: #dc3545 !important; 
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4); 
        transform: translateY(-8px);
    }
    
    .mood-card-btn:hover .text-white {
        color: #fff !important;
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