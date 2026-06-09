@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate-fadeIn">
                
                {{-- Header --}}
                <div class="card-header bg-dark p-4 border-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light rounded-circle me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <h4 class="text-white fw-bold m-0">
                            <i class="bi bi-card-checklist me-2 text-warning"></i>Buat Kuis Pilihan Ganda
                        </h4>
                    </div>
                    <span class="badge rounded-pill bg-success px-3 py-2" style="font-size: 11px;">Native Quiz Mode</span>
                </div>

                <div class="card-body p-5 bg-white">
                    <form action="{{ route('quiz.store') }}" method="POST" id="quizForm">
                        @csrf
                        
                        {{-- Langkah 1: Pilih Materi --}}
                        <div class="card bg-light border-0 rounded-4 p-4 mb-5">
                            <label class="form-label fw-bold text-dark fs-5">Langkah 1: Hubungkan ke Materi</label>
                            <select name="materi_id" class="form-select form-control-lg border-0 shadow-sm" required>
                                <option value="">-- Pilih Materi untuk Kuis ini --</option>
                                @foreach(\App\Models\Materi::where('user_id', auth()->id())->get() as $m)
                                    <option value="{{ $m->id }}">{{ $m->judul }} ({{ ucfirst($m->mood_category) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="my-5">

                        {{-- Langkah 2: Daftar Soal --}}
                        <div id="questions-container">
                            <label class="form-label fw-bold text-dark fs-5 mb-3">Langkah 2: Tulis Pertanyaan & Pembahasan</label>
                            
                            {{-- Template Soal Pertama (Indeks 0) --}}
                            <div class="question-item card border-1 rounded-4 p-4 mb-4 shadow-sm bg-light">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="badge bg-dark text-white rounded-pill px-3">Pertanyaan #1</span>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold mb-1">Teks Pertanyaan:</label>
                                    <textarea name="questions[text]" class="form-control border-0 shadow-sm" rows="2" placeholder="Tulis pertanyaan di sini..." required></textarea>
                                </div>

                                <div class="row g-3">
                                    @foreach(['a', 'b', 'c', 'd'] as $opt)
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" name="questions[{{ $opt }}]" class="form-control border-0 shadow-sm" placeholder="Pilihan {{ strtoupper($opt) }}" required>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold text-success small">Kunci Jawaban:</label>
                                        <select name="questions[correct]" class="form-select border-success text-success fw-bold shadow-sm" required>
                                            <option value="a">Pilihan A</option>
                                            <option value="b">Pilihan B</option>
                                            <option value="c">Pilihan C</option>
                                            <option value="d">Pilihan D</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold text-primary small">Penjelasan/Pembahasan (Rich Text Editor):</label>
                                        <div class="quill-editor bg-white rounded-3 mb-2" style="height: 150px;"></div>
                                        <input type="hidden" name="questions[explanation]" class="explanation-input">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Tambah Soal --}}
                        <div class="text-center mt-4">
                            <button type="button" id="addQuestion" class="btn btn-outline-dark rounded-pill px-4 shadow-sm">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Pertanyaan Lainnya
                            </button>
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

{{-- Quill Assets --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    // Konfigurasi Font
    var Font = Quill.import('formats/font');
    Font.whitelist = ['monalisa', 'sans-serif', 'serif', 'monospace'];
    Quill.register(Font, true);

    let quillInstances = [];

    function initQuill(container) {
        const editor = container.querySelector('.quill-editor');
        if (editor) {
            const quill = new Quill(editor, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'font': Font.whitelist }],
                        [{ 'header': [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                }
            });
            quillInstances.push({ quill: quill, container: container });
        }
    }

    // Inisialisasi Soal Pertama
    document.querySelectorAll('.question-item').forEach(item => initQuill(item));

    let questionCount = 1;

    document.getElementById('addQuestion').addEventListener('click', function() {
        const container = document.getElementById('questions-container');
        const newQuestion = document.createElement('div');
        newQuestion.className = 'question-item card border-1 rounded-4 p-4 mb-4 shadow-sm animate-fadeIn bg-light';
        
        newQuestion.innerHTML = `
            <div class="d-flex justify-content-between mb-3">
                <span class="badge bg-dark text-white rounded-pill px-3">Pertanyaan #${questionCount + 1}</span>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle remove-question">
                    <i class="bi bi-trash3-fill"></i>
                </button>
            </div>
            <div class="mb-3">
                <textarea name="questions[${questionCount}][text]" class="form-control border-0 shadow-sm" rows="2" placeholder="Tulis pertanyaan..." required></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6"><input type="text" name="questions[${questionCount}][a]" class="form-control border-0 shadow-sm" placeholder="Opsi A" required></div>
                <div class="col-md-6"><input type="text" name="questions[${questionCount}][b]" class="form-control border-0 shadow-sm" placeholder="Opsi B" required></div>
                <div class="col-md-6"><input type="text" name="questions[${questionCount}][c]" class="form-control border-0 shadow-sm" placeholder="Opsi C" required></div>
                <div class="col-md-6"><input type="text" name="questions[${questionCount}][d]" class="form-control border-0 shadow-sm" placeholder="Opsi D" required></div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <label class="small fw-bold text-success">Kunci Jawaban:</label>
                    <select name="questions[${questionCount}][correct]" class="form-select border-success shadow-sm" required>
                        <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="small fw-bold text-primary">Penjelasan:</label>
                    <div class="quill-editor bg-white rounded-3 mb-2" style="height: 150px;"></div>
                    <input type="hidden" name="questions[${questionCount}][explanation]" class="explanation-input">
                </div>
            </div>
        `;
        
        container.appendChild(newQuestion);
        initQuill(newQuestion);
        questionCount++;

        newQuestion.querySelector('.remove-question').addEventListener('click', function() {
            const index = quillInstances.findIndex(inst => inst.container === newQuestion);
            if (index > -1) quillInstances.splice(index, 1);
            newQuestion.remove();
        });
    });

    // Sinkronisasi data Quill ke input hidden saat submit
    document.getElementById('quizForm').onsubmit = function() {
        quillInstances.forEach(item => {
            const input = item.container.querySelector('.explanation-input');
            if(input) input.value = item.quill.root.innerHTML;
        });
        return true;
    };
</script>

<style>
    .ql-font-monalisa { font-family: 'Monalisa', monospace !important; }
    .ql-toolbar.ql-snow { border-radius: 10px 10px 0 0; background: #f8f9fa; border-color: #dee2e6; }
    .ql-container.ql-snow { border-radius: 0 0 10px 10px; border-color: #dee2e6; font-size: 14px; }
    .animate-fadeIn { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .input-group-text { min-width: 45px; justify-content: center; }
</style>
@endsection