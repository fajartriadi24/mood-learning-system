@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            {{-- Card Emotikode --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate-fadeIn">
                
                {{-- Header: Latar Hitam, Tombol Kembali, dan Teacher Mode --}}
                <div class="card-header bg-dark p-4 border-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        {{-- Tombol Kembali Bulat --}}
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light rounded-circle me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <h4 class="text-white fw-bold m-0">
                            <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Upload Materi Baru
                        </h4>
                    </div>
                    <span class="badge rounded-pill bg-success px-3 py-2" style="font-size: 11px;">Teacher Mode</span>
                </div>

                <div class="card-body p-5 bg-white">
                    <form action="{{ route('materi.store') }}" method="POST" id="materiForm">
                        @csrf
                        
                        {{-- Judul Materi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Judul Materi</label>
                            <input type="text" name="judul" class="form-control form-control-lg border-0 bg-light rounded-3 shadow-sm" placeholder="Contoh: Dasar Variabel Python" required>
                        </div>

                        {{-- Kategori Mood --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Kategori Mood Siswa</label>
                            <select name="mood_category" class="form-select form-control-lg border-0 bg-light rounded-3 shadow-sm" required>
                                <option value="semangat">🚀 Semangat (Materi Berat)</option>
                                <option value="biasa">😐 Biasa (Materi Standar)</option>
                                <option value="lelah">😴 Lelah (Materi Ringan)</option>
                                <option value="bingung">💡 Bingung (Materi Bantuan)</option>
                            </select>
                        </div>

                        {{-- Link YouTube --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Video Materi</label>
                            <div class="input-group">
                                <input type="url" name="video_url" id="input_url" class="form-control form-control-lg border-0 bg-light rounded-3 shadow-sm" placeholder="https://www.youtube.com/watch?v=..." required>
                            </div>
                            <small class="text-muted d-block mt-2" style="font-size: 11px;">*Link YouTube</small>
                        </div>

                        {{-- Isi Materi (Rich Text Editor) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Isi Materi (Tulisan & Gambar)</label>
                            <div id="editor-container" style="height: 350px;" class="bg-light rounded-3 border-0"></div>
                            <input type="hidden" name="deskripsi" id="deskripsi-input">
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-dark btn-lg py-3 fw-bold rounded-pill shadow-sm transition-btn">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan & Publikasikan Materi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Quill Editor --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    // 1. Daftarkan Font Monalisa ke Quill
    var Font = Quill.import('formats/font');
    Font.whitelist = ['monalisa', 'sans-serif', 'serif', 'monospace'];
    Quill.register(Font, true);

    var quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': ['monalisa', 'sans-serif', 'serif', 'monospace'] }],
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['image', 'clean']
            ]
        }
    });

    // 2. Logika Simpan: Pindahkan isi Quill ke Input Hidden sebelum Submit
    var form = document.getElementById('materiForm');
    form.onsubmit = function() {
        var deskripsiInput = document.getElementById('deskripsi-input');
        var content = quill.root.innerHTML;
        
        if(content === '<p><br></p>') content = '';
        
        if(content.trim().length === 0) {
            alert("Isi materi tidak boleh kosong!");
            return false;
        }

        deskripsiInput.value = content;
        return true;
    };
</script>

<style>
    @font-face {
        font-family: 'Monalisa';
        src: url('/fonts/Monalisa.ttf');
    }

    .ql-font-monalisa { font-family: 'Monalisa', monospace !important; }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="monalisa"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="monalisa"]::before {
        content: 'Monalisa';
        font-family: 'Monalisa', monospace;
    }

    .animate-fadeIn { animation: fadeIn 0.5s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .transition-btn:hover { transform: scale(1.02); background-color: #333 !important; }
    
    .ql-toolbar.ql-snow { border: none !important; background: #f8f9fa; border-radius: 15px 15px 0 0; border-bottom: 1px solid #ddd !important; }
    .ql-container.ql-snow { border: none !important; background: #f8f9fa; border-radius: 0 0 15px 15px; font-size: 16px; }
    
    input::placeholder { font-size: 14px; opacity: 0.7; }
</style>
@endsection