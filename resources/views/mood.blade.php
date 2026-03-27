@extends('layout')

@section('content')
<h3 class="text-center">Pilih Mood Belajar</h3>

<div class="row mt-4 text-center">
    <div class="col">
        <a href="/dashboard?mood=semangat" class="btn btn-success w-100">😊 Semangat</a>
    </div>
    <div class="col">
        <a href="/dashboard?mood=biasa" class="btn btn-secondary w-100">😐 Biasa</a>
    </div>
    <div class="col">
        <a href="/dashboard?mood=lelah" class="btn btn-warning w-100">😴 Lelah</a>
    </div>
    <div class="col">
        <a href="/dashboard?mood=bingung" class="btn btn-danger w-100">😕 Bingung</a>
    </div>
</div>
@endsection