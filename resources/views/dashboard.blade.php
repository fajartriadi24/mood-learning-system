@extends('layouts.app')

@section('content')
@php
    // Ambil mood dari session yang diset oleh MoodController
    $mood = session('current_mood');
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark fw-bold m-0">Dashboard Pembelajaran</h3>
    {{-- Button ganti mood --}}
    <a href="{{ route('mood') }}" class="btn btn-dark px-4 py-2 fw-bold shadow-lg" style="border-radius: 12px;">Ganti Mood</a>
</div>

{{-- Kartu Status Mood --}}
<div class="glass-box p-4 mb-4 d-flex align-items-center shadow-sm" style="background: rgba(255,255,255,0.8) !important;">
    <div class="fs-2 me-3">
        @if($mood == 'semangat') 🔥 
        @elseif($mood == 'biasa') 📘 
        @elseif($mood == 'lelah') 🎥 
        @elseif($mood == 'bingung') 🧠 
        @else ❓ @endif
    </div>
    <div class="text-dark">
        <p class="m-0 small opacity-75 fw-semibold">Status Belajar Kamu:</p>
        <h5 class="m-0 fw-bold">{{ ucfirst($mood ?? 'Belum memilih mood') }}</h5>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="glass-box p-4 shadow-sm" style="background: rgba(255,255,255,0.8) !important;">
            <h5 class="fw-bold mb-3 text-dark">Materi Rekomendasi:</h5>
            
            @if($mood == 'semangat')
                <div class="alert alert-success bg-opacity-75 border-success text-dark shadow-sm">
                    <h6><strong>🚀 Mode Fokus Tinggi</strong></h6>
                    <p class="m-0">Kamu sedang bersemangat! Sistem memberikan latihan algoritma kompleks untuk mengasah logika pemrogramanmu.</p>
                </div>
            @elseif($mood == 'biasa')
                <div class="alert alert-info bg-opacity-75 border-info text-dark shadow-sm">
                    <h6><strong>📖 Mode Standar</strong></h6>
                    <p class="m-0">Kondisi yang stabil. Silakan lanjutkan membaca modul variabel dan tipe data beserta kuis harian.</p>
                </div>
            @elseif($mood == 'lelah')
                <div class="alert alert-warning bg-opacity-75 border-warning text-dark shadow-sm">
                    <h6><strong>🎥 Mode Santai</strong></h6>
                    <p class="m-0">Jangan terlalu dipaksakan. Tonton video singkat berdurasi 5 menit tentang konsep dasar pemrograman agar tetap produktif.</p>
                </div>
            @elseif($mood == 'bingung')
                <div class="alert alert-danger bg-opacity-75 border-danger text-dark shadow-sm">
                    <h6><strong>💡 Mode Pemahaman</strong></h6>
                    <p class="m-0">Ada yang sulit dipahami? Mari kita ulangi penjelasan konsep dasar dengan ilustrasi yang lebih sederhana.</p>
                </div>
            @else
                <div class="p-3 text-center text-dark">
                    <p>Silakan <a href="{{ route('mood') }}" class="text-primary fw-bold text-decoration-none">pilih mood</a> kamu terlebih dahulu untuk melihat materi.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Area Tombol Aksi --}}
@if($mood)
<div class="mt-4 d-flex gap-3">
    {{-- PERBAIKAN: Mengubah <button> menjadi <a> agar bisa diklik menuju halaman materi --}}
    <a href="{{ route('materi.index') }}" class="btn btn-outline-dark px-5 py-2 fw-bold shadow-sm" style="border-radius: 12px; border-width: 2px;">
        Lanjutkan Materi
    </a>
    
    <button class="btn btn-outline-dark px-5 py-2 fw-bold shadow-sm" style="border-radius: 12px; border-width: 2px;">
        Mulai Quiz
    </button>
</div>
@endif

@endsection