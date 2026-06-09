@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- LOGIKA HITUNG KUNJUNGAN --}}
    @php
        use App\Models\Visit;
        use App\Models\Materi;
        use Carbon\Carbon;

        $totalVisits = Visit::count();
        $todayVisits = Visit::whereDate('created_at', Carbon::today())->count();
        $weekVisits = Visit::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $monthVisits = Visit::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $yearVisits = Visit::whereYear('created_at', Carbon::now()->year)->count();
    @endphp

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4" style="position: relative; z-index: 1001;">
        <div>
            <h3 class="text-dark fw-bold m-0">Dashboard {{ Auth::user()->role == 'guru' ? 'Pengajar' : 'Pembelajaran' }}</h3>
            <p class="text-muted small m-0">Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>
        </div>
        @if(Auth::user()->role == 'siswa')
           <a href="{{ route('mood') }}" class="btn btn-dark px-4 py-2 fw-bold shadow-sm custom-btn-mood">Ganti Mood</a>
        @endif
    </div>

    @if(Auth::user()->role == 'guru')
        {{-- STATISTIK ATAS --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 text-white rounded-4 h-100" style="background: linear-gradient(45deg, #000, #333);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="opacity-75 small">Total Materi Sistem</h6>
                            {{-- Diperbaiki: Menghitung semua materi di sistem --}}
                            <h2 class="fw-bold m-0">{{ Materi::count() }}</h2>
                        </div>
                        <div class="fs-1 opacity-20">📝</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <a href="#daftar-siswa" class="text-decoration-none h-100">
                    <div class="card border-0 shadow-sm p-4 text-white rounded-4 h-100" style="background: #00c3ff;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="opacity-75 small">Pengguna Terdaftar</h6>
                                <h2 class="fw-bold m-0">{{ \App\Models\User::where('role', 'siswa')->count() }}</h2>
                            </div>
                            <div class="fs-1 opacity-20">👨‍💻</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 text-white rounded-4 h-100" style="background: #20570b;">
                    <div class="d-flex justify-content-between align-items-center mb-2 text-white">
                        <div>
                            <h6 class="opacity-75 small">Kunjungan Website</h6>
                            <h2 class="fw-bold m-0">{{ $totalVisits }}</h2>
                        </div>
                        <div class="fs-1 opacity-20">📈</div>
                    </div>
                    <div class="row g-0 mt-2 border-top border-white border-opacity-10 pt-2 text-white">
                        <div class="col-6">
                            <p class="m-0 opacity-75" style="font-size: 10px;">Hari ini: <strong>{{ $todayVisits }}</strong></p>
                            <p class="m-0 opacity-75" style="font-size: 10px;">Bulan ini: <strong>{{ $monthVisits }}</strong></p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="m-0 opacity-75" style="font-size: 10px;">Minggu ini: <strong>{{ $weekVisits }}</strong></p>
                            <p class="m-0 opacity-75" style="font-size: 10px;">Tahun ini: <strong>{{ $yearVisits }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT ROW --}}
        <div class="row g-4">
            <div class="col-lg-8">
                {{-- PREVIEW MATERI --}}
                <div class="glass-box p-4 shadow-sm mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-play-circle-fill me-2"></i>Preview Materi Terkini</h5>
                    <div id="preview-container">
                        {{-- Mengambil materi terbaru di sistem agar semua guru tahu upload-an terakhir --}}
                        @php $latest = Materi::latest()->first(); @endphp
                        @if($latest)
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden bg-dark shadow-sm" id="main-preview-holder">
                                        @if($latest->video_url)
                                            <iframe src="{{ $latest->video_url }}" frameborder="0" allowfullscreen></iframe>
                                        @else
                                            <video id="main-video" controls class="w-100">
                                                <source id="video-source" src="{{ asset('storage/' . $latest->video_path) }}" type="video/mp4">
                                            </video>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 id="preview-title" class="fw-bold text-success">{{ $latest->judul }}</h6>
                                    <p class="small text-muted mb-1">Diupload oleh: {{ $latest->user->name ?? 'Admin' }}</p>
                                    <div id="preview-desc" class="small text-dark mt-2" style="max-height: 150px; overflow-y: auto; line-height: 1.6;">
                                        {!! $latest->deskripsi !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TABEL KELOLA MATERI (INTEGRASI PENUH) --}}
                <div class="glass-box p-4 shadow-sm mb-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark m-0"><i class="bi bi-collection-play me-2"></i>Database Materi Pengajar</h5>
                        <div class="btn-group shadow-sm">
                            @php $currentMood = request('mood', 'semangat'); @endphp
                            <a href="?mood=semangat" class="btn btn-sm {{ $currentMood == 'semangat' ? 'btn-dark' : 'btn-outline-dark' }}">🚀</a>
                            <a href="?mood=biasa" class="btn btn-sm {{ $currentMood == 'biasa' ? 'btn-dark' : 'btn-outline-dark' }}">😐</a>
                            <a href="?mood=lelah" class="btn btn-sm {{ $currentMood == 'lelah' ? 'btn-dark' : 'btn-outline-dark' }}">😴</a>
                            <a href="?mood=bingung" class="btn btn-sm {{ $currentMood == 'bingung' ? 'btn-dark' : 'btn-outline-dark' }}">💡</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light small">
                                <tr><th>JUDUL</th><th>PENGUPLOAD</th><th class="text-end">AKSI</th></tr>
                            </thead>
                            <tbody>
                                {{-- Diperbaiki: Menampilkan $allMateri dari controller agar terintegrasi --}}
                                @forelse($allMateri->where('mood_category', $currentMood) as $m)
                                <tr>
                                    <td><div class="fw-bold text-dark small">{{ $m->judul }}</div></td>
                                    <td><span class="badge bg-light text-dark border small">{{ $m->user->name ?? 'Guru' }}</span></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-success px-3 fw-bold rounded-pill btn-preview" style="font-size: 11px;" 
                                                data-video-url="{{ $m->video_url }}"
                                                data-video-path="{{ $m->video_path ? asset('storage/' . $m->video_path) : '' }}" 
                                                data-judul="{{ $m->judul }}">
                                                <div class="d-none deskripsi-source">{!! $m->deskripsi !!}</div> Lihat
                                            </button>
                                            
                                            {{-- Hanya munculkan tombol edit/hapus jika materi milik guru yang sedang login --}}
                                            @if($m->user_id == Auth::id())
                                                <a href="{{ route('materi.edit', $m->id) }}" class="text-primary"><i class="bi bi-pencil-square"></i></a>
                                                <form action="{{ route('materi.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0"><i class="bi bi-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-4 small text-muted">Materi kategori ini belum tersedia</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- KELOLA QUIZ --}}
                <div class="glass-box p-4 shadow-sm mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-patch-question me-2"></i>Kelola Quiz</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light small">
                                <tr><th>JUDUL MATERI</th><th class="text-end">AKTIVITAS</th></tr>
                            </thead>
                            <tbody>
                                {{-- Tetap menampilkan kuis milik pengajar bersangkutan --}}
                                @forelse(Materi::where('user_id', Auth::id())->whereHas('quizzes')->latest()->get() as $q)
                                <tr>
                                    <td><div class="fw-bold text-dark small">{{ $q->judul }}</div></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                            <a href="{{ route('quiz.show', $q->id) }}" class="btn btn-sm btn-success px-3 fw-bold rounded-pill text-decoration-none" style="font-size: 11px;">Lihat Quiz</a>
                                            <a href="{{ route('quiz.edit', $q->id) }}" class="text-primary"><i class="bi bi-pencil-square"></i></a>
                                            <form action="{{ route('quiz.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center py-4 small text-muted">Belum ada kuis yang Anda buat</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- DAFTAR PENGGUNA --}}
                <div id="daftar-siswa" class="glass-box p-4 shadow-sm mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-people-fill me-2"></i>Daftar Pengguna</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light small text-muted">
                                <tr><th>NAMA</th><th>EMAIL</th><th class="text-end">AKTIVITAS</th></tr>
                            </thead>
                            <tbody>
                                @isset($siswas)
                                @foreach($siswas as $siswa)
                                <tr>
                                    <td class="small fw-bold text-dark">{{ $siswa->name }}</td>
                                    <td class="small text-muted">{{ $siswa->email }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                             <button type="button" class="btn btn-sm btn-success px-3 fw-bold rounded-pill btn-preview" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#detailSiswa-{{ $siswa->id }}">Detail Progres</button>
                                            <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                 <button type="submit" class="btn btn-link text-danger p-0 border-0"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="col-lg-4">
                <div class="glass-box p-4 shadow-sm mb-4 bg-white sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-3 text-dark small text-center">Upload Menu Pembelajaran</h5>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('materi.create') }}" class="btn btn-dark w-100 py-3 fw-bold rounded-4 shadow-sm d-flex flex-column align-items-center" style="font-size: 11px;">
                                <i class="bi bi-cloud-arrow-up fs-4 mb-1"></i> Materi
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('quiz.create') }}" class="btn btn-outline-dark w-100 py-3 fw-bold rounded-4 shadow-sm d-flex flex-column align-items-center" style="font-size: 11px;">
                                <i class="bi bi-pencil-square fs-4 mb-1"></i> Quiz
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL AREA --}}
        @isset($siswas)
        @foreach($siswas as $siswa)
        <div class="modal fade" id="detailSiswa-{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header bg-dark text-white border-0 py-3">
                        <h5 class="modal-title fw-bold">Progres: {{ $siswa->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-dark text-start">
                        <div class="row g-3 mb-4 text-center">
                            <div class="col-md-4"><div class="p-3 bg-light rounded-4 border h-100"><h2>{{ $siswa->quizResults->count() }}</h2><small class="fw-bold">KUIS SELESAI</small></div></div>
                            <div class="col-md-4"><div class="p-3 bg-light rounded-4 border text-primary h-100"><h2>{{ round($siswa->quizResults->avg('score') ?? 0) }}</h2><small class="fw-bold">RATA-RATA NILAI</small></div></div>
                            <div class="col-md-4"><div class="p-3 bg-light rounded-4 border text-success h-100"><h2>{{ $siswa->quizResults->where('score', '>=', 70)->count() }}</h2><small class="fw-bold">LULUS</small></div></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm small align-middle">
                                <thead class="text-muted border-bottom text-dark">
                                    <tr><th>Materi</th><th class="text-center">Nilai</th><th class="text-center">Status</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($siswa->quizResults as $result)
                                    <tr class="text-dark">
                                        <td class="fw-bold py-3">{{ $result->materi->judul ?? 'Materi' }}</td>
                                        <td class="text-center"><span class="badge {{ $result->score >= 70 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">{{ $result->score }}</span></td>
                                        <td class="text-center">
                                            @if($result->score >= 70)
                                                <span class="text-success fw-bold">Selesai</span>
                                            @else
                                                <span class="text-danger fw-bold">Mengulang</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endisset

    @else
        {{-- TAMPILAN SISWA --}}
        @php $mood = session('current_mood'); @endphp
        <div class="glass-box p-4 mb-4 d-flex align-items-center shadow-sm" style="background: rgba(255,255,255,0.8) !important;">
            <div class="fs-2 me-3">@if($mood == 'semangat') 🔥 @elseif($mood == 'biasa') 📘 @elseif($mood == 'lelah') 🎥 @elseif($mood == 'bingung') 🧠 @else ❓ @endif</div>
            <div class="text-dark">
                <p class="m-0 small opacity-75 fw-semibold">Status Belajar:</p>
                <h5 class="m-0 fw-bold text-success">{{ ucfirst($mood ?? 'Pilih Mood') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="glass-box p-4 shadow-sm" style="background: rgba(255,255,255,0.8) !important;">
                    <h5 class="fw-bold mb-3 text-dark">Rekomendasi Kondisi:</h5>
                    @if($mood)
                        <div class="alert alert-info border-0 shadow-sm py-4 rounded-4">
                            <h6 class="fw-bold">Mode {{ ucfirst($mood) }} Aktif</h6>
                            <p class="m-0">Materi disesuaikan agar belajar maksimal.</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('materi.index') }}" class="btn btn-dark px-4 py-2 fw-bold shadow-sm custom-btn-mood">Buka Materi Belajar</a>
                        </div>
                    @else
                        <div class="p-5 text-center text-dark">
                            <p class="mb-3">Silakan pilih mood.</p>
                            <a href="{{ route('mood') }}" class="btn btn-dark rounded-pill px-5">Pilih Mood</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewHolder = document.getElementById('main-preview-holder');
    document.querySelectorAll('.btn-preview').forEach(button => {
        button.addEventListener('click', function() {
            const videoUrl = this.getAttribute('data-video-url');
            const videoPath = this.getAttribute('data-video-path');
            document.getElementById('preview-title').innerText = this.getAttribute('data-judul');
            document.getElementById('preview-desc').innerHTML = this.querySelector('.deskripsi-source').innerHTML;
            if (videoUrl) {
                previewHolder.innerHTML = `<iframe src="${videoUrl}" frameborder="0" allowfullscreen class="w-100 h-100"></iframe>`;
            } else if (videoPath) {
                previewHolder.innerHTML = `<video controls class="w-100"><source src="${videoPath}" type="video/mp4"></video>`;
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
});
</script>

<style>
    .modal-backdrop { background-color: transparent !important; opacity: 0 !important; pointer-events: none !important; }
    .modal { background: rgba(0, 0, 0, 0.5) !important; z-index: 99999 !important; }
    .glass-box { border-radius: 25px; border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease; }
    video, iframe { border-radius: 15px; width: 100%; height: 100%; min-height: 200px; }
    .card:hover { transform: translateY(-5px); transition: 0.3s; }
</style>
@endsection