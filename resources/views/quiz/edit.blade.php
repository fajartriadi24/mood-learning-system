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
                            <i class="bi bi-pencil-square me-2 text-warning"></i>Edit & Kelola Kuis
                        </h4>
                    </div>
                    <span class="badge rounded-pill bg-primary px-3 py-2" style="font-size: 11px;">Materi: {{ $materi->judul }}</span>
                </div>

                <div class="card-body p-5 bg-white">
                    <form action="{{ route('quiz.update', $materi->id) }}" method="POST" id="editQuizForm">
                        @csrf
                        @method('PUT')
                        
                        <div id="questions-container">
                            <label class="form-label fw-bold text-dark fs-5 mb-3">Daftar Pertanyaan</label>
                            
                            @forelse($materi->quizzes as $index => $quiz)
                            <div class="question-item card border-1 rounded-4 p-4 mb-4 shadow-sm bg-light" data-index="{{ $index }}">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="badge bg-dark text-white rounded-pill px-3">Pertanyaan #{{ $index + 1 }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle remove-question">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold mb-1">Teks Pertanyaan:</label>
                                    <textarea name="questions[{{ $index }}][text]" class="form-control border-0 shadow-sm" rows="2" required>{{ $quiz->question_text }}</textarea>
                                </div>

                                <div class="row g-3">
                                    @foreach(['a', 'b', 'c', 'd'] as $opt)
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" name="questions[{{ $index }}][{{ $opt }}]" class="form-control border-0 shadow-sm" value="{{ $quiz->{'option_'.$opt} }}" required>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold text-success small">Kunci Jawaban:</label>
                                        <select name="questions[{{ $index }}][correct]" class="form-select border-success text-success fw-bold shadow-sm" required>
                                            <option value="a" {{ $quiz->correct_answer == 'a' ? 'selected' : '' }}>Pilihan A</option>
                                            <option value="b" {{ $quiz->correct_answer == 'b' ? 'selected' : '' }}>Pilihan B</option>
                                            <option value="c" {{ $quiz->correct_answer == 'c' ? 'selected' : '' }}>Pilihan C</option>
                                            <option value="d" {{ $quiz->correct_answer == 'd' ? 'selected' : '' }}>Pilihan D</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold text-primary small">Penjelasan/Pembahasan:</label>
                                        <div class="quill-editor bg-white rounded-3 mb-2" style="height: 200px;">
                                            {!! $quiz->explanation !!}
                                        </div>
                                        <input type="hidden" name="questions[{{ $index }}][explanation]" class="explanation-input">
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-muted my-5" id="empty-msg">Belum ada soal.</p>
                            @endforelse
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" id="addQuestionEdit" class="btn btn-outline-dark rounded-pill px-4 shadow-sm">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Pertanyaan Baru
                            </button>
                        </div>

                        <div class="d-grid mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold rounded-pill shadow">
                                <i class="bi bi-save2 me-2"></i>Simpan Perubahan Kuis
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
    // 1. Daftarkan Font Monalisa & Lainnya ke Quill
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
                        [{ 'font': Font.whitelist }], // Dropdown Font
                        [{ 'header': [1, 2, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }], // Rata Kanan Kiri
                        ['clean']
                    ]
                }
            });
            quillInstances.push({ quill: quill, container: container });
        }
    }

    // Load awal
    document.querySelectorAll('.question-item').forEach(item => initQuill(item));

    // Tambah Soal Baru (JS)
    let questionCount = {{ $materi->quizzes->count() }};
    document.getElementById('addQuestionEdit').addEventListener('click', function() {
        const container = document.getElementById('questions-container');
        const emptyMsg = document.getElementById('empty-msg');
        if(emptyMsg) emptyMsg.remove();

        const newQuestion = document.createElement('div');
        newQuestion.className = 'question-item card border-1 rounded-4 p-4 mb-4 shadow-sm border-warning bg-white';
        newQuestion.innerHTML = `
            <div class="d-flex justify-content-between mb-3">
                <span class="badge bg-warning text-dark rounded-pill px-3">Pertanyaan Baru</span>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle remove-question"><i class="bi bi-trash3-fill"></i></button>
            </div>
            <div class="mb-3">
                <textarea name="questions[${questionCount}][text]" class="form-control border-light bg-light" rows="2" placeholder="Tulis pertanyaan..." required></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6"><input type="text" name="questions[${questionCount}][a]" class="form-control bg-light" placeholder="Opsi A" required></div>
                <div class="col-md-6"><input type="text" name="questions[${questionCount}][b]" class="form-control bg-light" placeholder="Opsi B" required></div>
                <div class="col-md-6"><input type="text" name="questions[${questionCount}][c]" class="form-control bg-light" placeholder="Opsi C" required></div>
                <div class="col-md-6"><input type="text" name="questions[${questionCount}][d]" class="form-control bg-light" placeholder="Opsi D" required></div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <label class="small fw-bold text-success">Kunci Jawaban:</label>
                    <select name="questions[${questionCount}][correct]" class="form-select border-success" required>
                        <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="small fw-bold text-primary">Penjelasan:</label>
                    <div class="quill-editor bg-white rounded-3 mb-2" style="height: 200px;"></div>
                    <input type="hidden" name="questions[${questionCount}][explanation]" class="explanation-input">
                </div>
            </div>
        `;
        container.appendChild(newQuestion);
        initQuill(newQuestion);
        questionCount++;
    });

    // Sinkronisasi data saat Simpan
    document.getElementById('editQuizForm').onsubmit = function() {
        quillInstances.forEach(item => {
            const input = item.container.querySelector('.explanation-input');
            if(input) input.value = item.quill.root.innerHTML;
        });
        return true;
    };

    // Hapus
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-question')) {
            const item = e.target.closest('.question-item');
            quillInstances = quillInstances.filter(inst => inst.container !== item);
            item.remove();
        }
    });
</script>

<style>
    /* Agar Font Monalisa muncul di label dropdown Quill */
    .ql-font-monalisa { font-family: 'Monalisa', monospace !important; }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="monalisa"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="monalisa"]::before {
        content: 'Monalisa';
        font-family: 'Monalisa';
    }

    .ql-toolbar.ql-snow { border-radius: 12px 12px 0 0; background: #f8f9fa; border: 1px solid #dee2e6; }
    .ql-container.ql-snow { border-radius: 0 0 12px 12px; border: 1px solid #dee2e6; font-size: 15px; }
    .animate-fadeIn { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection