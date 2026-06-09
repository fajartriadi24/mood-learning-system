@extends('layouts.app')

@section('content')
<div class="dashboard">
    <div class="container py-5">
        {{-- Header Materi --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark">Materi </h2>
                <p class="text-muted m-0">Halo, <strong>{{ Auth::user()->name }}</strong>! Mode belajar aktif: 
                    <span class="badge bg-primary px-3 py-2 ms-2">
                        {{ ucfirst($mood ?? 'Biasa') }}
                    </span>
                </p>
            </div>
            <div class="d-flex gap-2">
                @if(Auth::user()->role == 'guru')
                    <a href="{{ route('materi.create') }}" class="btn btn-success shadow-sm px-3 fw-bold text-white border-0">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Video
                    </a>
                    <a href="{{ route('quiz.create') }}" class="btn btn-warning shadow-sm px-3 fw-bold text-white border-0">
                        <i class="bi bi-plus-square-fill me-1"></i> Tambah Kuis
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- AREA KONTEN MATERI --}}
                <div class="glass-box p-5 mb-4 shadow-sm scrollable-content" style="background: rgba(255,255,255,0.95) !important; border: 1px solid rgba(0,0,0,0.1) !important;">
                    <div class="content-wrapper">
                        @if($materis->count() > 0)
                            @foreach($materis as $index => $item)
                                @php
                                    // 1. LOGIKA SINKRONISASI: Sekarang mengecek ke tabel QuizResult
                                    $isFinishedAnyMood = \App\Models\QuizResult::where('user_id', Auth::id())
                                        ->where('materi_id', $item->id)
                                        ->exists();

                                    // 2. LOGIKA PENGUNCI: Sekarang mengecek progres kuis sebelumnya di tabel QuizResult
                                    $isLocked = false;
                                    if ($index > 0) {
                                        $prevMateri = $materis[$index - 1];
                                        $prevFinished = \App\Models\QuizResult::where('user_id', Auth::id())
                                            ->where('materi_id', $prevMateri->id)
                                            ->exists();

                                        if (!$prevFinished) {
                                            $isLocked = true;
                                        }
                                    }

                                    $canToggle = $isFinishedAnyMood;
                                @endphp

                                <div class="animate-fadeIn mb-4 border rounded-4 bg-white overflow-hidden {{ $isLocked ? 'locked-section' : '' }}">
                                    
                                    {{-- HEADER ACCORDION (LACI) --}}
                                    <div class="p-3 d-flex justify-content-between align-items-center bg-light border-bottom" 
                                         style="cursor: {{ $isLocked ? 'default' : ($canToggle ? 'pointer' : 'default') }}" 
                                         @if(!$isLocked && $canToggle) data-bs-toggle="collapse" data-bs-target="#collapse-{{ $item->id }}" @endif>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $icons = ['semangat' => '🚀', 'biasa' => '😐', 'lelah' => '😴', 'bingung' => '💡'];
                                                $currentIcon = $icons[$item->mood_category] ?? '📖';
                                            @endphp
                                            <span class="fs-4 me-2">{{ $currentIcon }}</span>
                                            <h5 class="fw-bold m-0 {{ $isFinishedAnyMood ? 'text-success' : 'text-dark' }}">
                                                {{ $item->judul }}
                                                @if($isFinishedAnyMood) <i class="bi bi-check-circle-fill ms-1"></i> @endif
                                            </h5>
                                        </div>
                                        @if($isLocked)
                                            <i class="bi bi-lock-fill text-muted"></i>
                                        @elseif(!$canToggle)
                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">Sedang Dipelajari</span>
                                        @else
                                            <i class="bi bi-chevron-down text-muted"></i>
                                        @endif
                                    </div>

                                    {{-- ISI ACCORDION (KONTEN) --}}
                                    <div id="collapse-{{ $item->id }}" class="collapse {{ !$isLocked && !$isFinishedAnyMood ? 'show' : '' }}">
                                        <div class="p-4">
                                            @if($isLocked)
                                                <div class="text-center py-4 bg-light rounded-4 border border-dashed shadow-sm">
                                                    <div class="fs-1 text-muted mb-2">🔒</div>
                                                    <h5 class="fw-bold text-muted">Materi Terkunci</h5>
                                                    <p class="small text-muted mb-0">Selesaikan kuis pada materi sebelumnya untuk membuka bagian ini.</p>
                                                </div>
                                            @else
                                                {{-- Alert Mood --}}
                                                @if($item->mood_category == 'semangat')
                                                    <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm small">
                                                        <strong>🔥 Level Tinggi:</strong> Kamu sedang On-Fire! Pelajari materi lengkap.
                                                    </div>
                                                @elseif($item->mood_category == 'lelah')
                                                    <div class="alert alert-warning border-0 rounded-4 mb-4 shadow-sm small">
                                                        <strong>☕ Level Ringan:</strong> Santai sejenak. Fokus lihat video singkat.
                                                    </div>
                                                @elseif($item->mood_category == 'bingung')
                                                    <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm small">
                                                        <strong>💡 Level Bantuan:</strong> Jangan pusing. Mari kita pahami pelan-pelan.
                                                    </div>
                                                @endif

                                                {{-- Video (Mendukung Link YouTube & File Lokal) --}}
                                                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg mb-4 bg-dark">
                                                    @if($item->video_url)
                                                        {{-- Jika ada link YouTube --}}
                                                        <iframe src="{{ $item->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                                    @elseif($item->video_path)
                                                        {{-- Jika ada file lokal --}}
                                                        <video controls class="w-100" 
                                                               id="video-{{ $item->id }}" 
                                                               onended="markVideoDone({{ $item->id }})"
                                                               onplay="initVideoTracker({{ $item->id }})">
                                                            <source src="{{ asset('storage/' . $item->video_path) }}" type="video/mp4">
                                                        </video>
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center text-white">Video tidak tersedia</div>
                                                    @endif
                                                </div>

                                                {{-- Deskripsi Materi --}}
                                                <div class="materi-deskripsi mb-4">
                                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-text me-2"></i>Penjelasan Materi:</h6>
                                                    <div class="p-3 rounded-4 text-dark" style="background-color: #fdfdfd; border: 1px solid #eee; line-height: 1.8;">
                                                        @if(!empty($item->deskripsi))
                                                            {!! $item->deskripsi !!}
                                                        @else
                                                            <p class="text-muted small m-0">Tidak ada penjelasan teks untuk materi ini.</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="mt-4 pt-3 border-top border-light d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                    <div>
                                                        @if($isFinishedAnyMood)
                                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $item->id }}">
                                                                <i class="bi bi-chevron-up me-1"></i> Tutup Materi
                                                            </button>
                                                        @else
                                                            <p class="text-danger fw-bold small mb-2"><i class="bi bi-info-circle me-1"></i> Pelajari dan Kerjakan Quiznya untuk lanjut materi.</p>
                                                        @endif
                                                        <p class="text-muted small m-0">Diunggah oleh: <strong>{{ $item->user->name ?? 'Pengajar' }}</strong></p>
                                                    </div>

                                                    <div class="d-flex gap-2">
                                                        @if(\App\Models\Quiz::where('materi_id', $item->id)->exists())
                                                            <a href="{{ route('quiz.show', $item->id) }}" class="btn btn-dark btn-sm px-3 py-2 fw-bold rounded-pill shadow transition-btn">
                                                                <i class="bi bi-pencil-square me-1 text-warning"></i> Quiz
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <span style="font-size: 50px;">📂</span>
                                <h4 class="mt-3 text-muted">Belum ada materi untuk mood <strong>{{ $mood }}</strong>.</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SIDEBAR PROGRES --}}
            <div class="col-lg-4">
                <div class="glass-box p-4 shadow-sm position-sticky" style="top: 20px; background: rgba(255,255,255,0.9) !important; border: 1px solid rgba(0,0,0,0.1) !important;">
                    <h5 class="fw-bold text-dark mb-4 text-center">Informasi Belajar</h5>
                    
                    <div class="alert alert-info border-0 rounded-4 small">
                        <strong>Tips Pembelajaran:</strong> Materi disesuaikan dengan mood <strong>{{ $mood }}</strong>.
                    </div>

                    @php
                        $totalMateriCount = $materis->count();
                        $materiSelesaiCount = \App\Models\QuizResult::where('user_id', Auth::id())
                            ->whereIn('materi_id', $materis->pluck('id'))
                            ->count();

                        $materiTerkunciCount = max(0, $totalMateriCount - ($materiSelesaiCount + ($totalMateriCount > $materiSelesaiCount ? 1 : 0)));
                        $progressPercent = $totalMateriCount > 0 ? ($materiSelesaiCount / $totalMateriCount) * 100 : 0;
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2 small text-dark">
                            <span>Materi Terbuka</span>
                            <span class="fw-bold">{{ $totalMateriCount }} File</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-dark">
                            <span>Materi Selesai</span>
                            <span class="fw-bold text-success">{{ $materiSelesaiCount }} Selesai</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small text-dark">
                            <span>Materi Terkunci</span>
                            <span class="fw-bold text-danger">{{ $materiTerkunciCount }} Terkunci</span>
                        </div>
                        
                        <div class="progress" style="height: 12px; border-radius: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <div class="text-center mt-2">
                            <small class="fw-bold text-success">{{ round($progressPercent) }}% Selesai</small>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="mt-4 text-center">
                        <a href="{{ route('dashboard') }}" class="text-dark small fw-bold text-decoration-none opacity-75">← Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard { min-height: 100vh; width: 100%; }
    .scrollable-content { max-height: 800px; overflow-y: auto; scrollbar-width: thin; }
    .scrollable-content::-webkit-scrollbar { width: 6px; }
    .scrollable-content::-webkit-scrollbar-thumb { background-color: rgba(0,0,0,0.1); border-radius: 10px; }
    .animate-fadeIn { animation: fadeIn 0.8s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .glass-box { border-radius: 25px; }
    video { outline: none; }
    .transition-btn { transition: all 0.3s ease; }
    .transition-btn:hover { transform: scale(1.05); }
    .locked-section { opacity: 0.6; pointer-events: none; filter: grayscale(0.5); }
    .bg-light { background-color: #f8f9fa !important; }
</style>

<script>
let maxTimeReached = {};

function initVideoTracker(id) {
    const video = document.getElementById('video-' + id);
    if (!video) return;
    if (!maxTimeReached[id]) maxTimeReached[id] = 0;

    video.addEventListener('seeking', function() {
        if (video.currentTime > maxTimeReached[id]) {
            video.currentTime = maxTimeReached[id];
        }
    });

    video.addEventListener('timeupdate', function() {
        if (!video.seeking) {
            if (video.currentTime > maxTimeReached[id]) {
                if (video.currentTime - maxTimeReached[id] > 2) {
                    video.currentTime = maxTimeReached[id];
                } else {
                    maxTimeReached[id] = video.currentTime;
                }
            }
        }
    });
}

function markVideoDone(materiId) {
    fetch(`/materi/${materiId}/video-done`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
}
</script>
@endsection