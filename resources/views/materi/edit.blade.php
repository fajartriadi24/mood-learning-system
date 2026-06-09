@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate-fadeIn">
                
                {{-- Header --}}
                <div class="card-header bg-dark p-4 border-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light rounded-circle me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <h4 class="text-white fw-bold m-0">
                            <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Materi
                        </h4>
                    </div>
                    <span class="badge rounded-pill bg-success px-3 py-2" style="font-size: 11px;">Teacher Mode</span>
                </div>

                <div class="card-body p-5 bg-white">
                    {{-- Hapus enctype karena sudah tidak upload file video --}}
                    <form action="{{ route('materi.update', $materi->id) }}" method="POST" id="editMateriForm">
                        @csrf
                        @method('PUT')

                        {{-- Judul Materi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Judul Materi</label>
                            <input type="text" name="judul" class="form-control form-control-lg border-0 bg-light rounded-3" 
                                   value="{{ $materi->judul }}" required>
                        </div>

                        {{-- Kategori Mood --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Kategori Mood</label>
                            <select name="mood_category" class="form-select form-control-lg border-0 bg-light rounded-3" required>
                                <option value="semangat" {{ $materi->mood_category == 'semangat' ? 'selected' : '' }}>🚀 Semangat</option>
                                <option value="biasa" {{ $materi->mood_category == 'biasa' ? 'selected' : '' }}>😐 Biasa</option>
                                <option value="lelah" {{ $materi->mood_category == 'lelah' ? 'selected' : '' }}>😴 Lelah</option>
                                <option value="bingung" {{ $materi->mood_category == 'bingung' ? 'selected' : '' }}>💡 Bingung</option>
                            </select>
                        </div>

                        {{-- Update Link YouTube --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Video Materi</label>
                            <div class="input-group">
                                
                                <input type="url" name="video_url" id="input_url" class="form-control form-control-lg border-0 bg-light rounded-end-3" 
                                       placeholder="https://www.youtube.com/watch?v=..." 
                                       value="{{ $materi->video_url }}" required>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">*Link YouTube</small>
                            </div>
                        </div>

                        {{-- Deskripsi Materi (Quill Editor) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Deskripsi Materi</label>
                            <div id="editor-container" style="height: 300px;" class="bg-light rounded-3 border-0">
                                {!! $materi->deskripsi !!}
                            </div>
                            <input type="hidden" name="deskripsi" id="deskripsi-input">
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-dark btn-lg py-3 fw-bold rounded-pill shadow-sm transition-btn">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan Materi
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
    var form = document.getElementById('editMateriForm');
    form.onsubmit = function() {
        var deskripsiInput = document.getElementById('deskripsi-input');
        var content = quill.root.innerHTML;
        
        if(content === '<p><br></p>') content = '';
        
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
</style>
@endsection