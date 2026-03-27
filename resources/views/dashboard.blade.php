@extends('layout')

@section('content')

@php
$mood = request('mood');
@endphp

<h3 class="text-white mb-4">Dashboard</h3>

<!-- MOOD INFO -->
<div class="glass p-3 mb-4 text-white d-flex justify-content-between align-items-center">
    <div>
        Mood Kamu: 
        <b>{{ $mood ?? 'Belum dipilih' }}</b>
    </div>
    <a href="/mood" class="btn btn-custom btn-sm">Ganti Mood</a>
</div>

<!-- MATERI -->
<div class="row">

@if($mood == 'semangat')
    <div class="col-md-4">
        <div class="glass p-3 text-white">
            <h5>🔥 Latihan Sulit</h5>
            <p>Soal menantang untuk meningkatkan skill</p>
        </div>
    </div>

@elseif($mood == 'biasa')
    <div class="col-md-4">
        <div class="glass p-3 text-white">
            <h5>📘 Materi Standar</h5>
            <p>Belajar konsep dasar + latihan</p>
        </div>
    </div>

@elseif($mood == 'lelah')
    <div class="col-md-4">
        <div class="glass p-3 text-white">
            <h5>🎥 Video Ringan</h5>
            <p>Materi santai dalam bentuk video</p>
        </div>
    </div>

@elseif($mood == 'bingung')
    <div class="col-md-4">
        <div class="glass p-3 text-white">
            <h5>🧠 Penjelasan Dasar</h5>
            <p>Pengulangan konsep fundamental</p>
        </div>
    </div>

@else
    <div class="glass p-3 text-white">
        <p>Silakan pilih mood terlebih dahulu</p>
    </div>
@endif

</div>

<!-- QUIZ -->
@if($mood)
<a href="/quiz" class="btn btn-custom mt-4">Mulai Quiz</a>
@endif

@endsection