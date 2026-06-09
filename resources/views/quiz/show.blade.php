@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate-fadeIn">
                
                <div class="card-header bg-dark p-4 border-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('materi.index') }}" class="btn btn-sm btn-outline-light rounded-circle me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <h4 class="text-white fw-bold m-0">Kuis: {{ $materi->judul }}</h4>
                    </div>
                </div>

                <div class="card-body p-5 bg-white">
                    @php
                        $hasilLama = \App\Models\QuizResult::where('user_id', Auth::id())
                                    ->where('materi_id', $materi->id)
                                    ->first();
                    @endphp

                    {{-- 1. TAMPILAN HASIL --}}
                    <div id="resultSection" class="{{ $hasilLama ? '' : 'd-none' }} text-center animate-fadeIn">
                        <h2 class="fw-bold mb-0">Skor Kamu:</h2>
                        <div class="display-1 fw-bold {{ ($hasilLama && $hasilLama->score >= 80) ? 'text-success' : 'text-danger' }} mb-3" id="finalScore">
                            {{ $hasilLama ? $hasilLama->score : 0 }}
                        </div>
                        <p class="mb-4 fs-5" id="scoreMessage">
                            @if($hasilLama && $hasilLama->score >= 80)
                                Selamat! Kamu memahami materi ini dengan sangat baik.
                            @else
                                Maaf, skor kamu belum mencapai batas minimal 80.
                            @endif
                        </p>
                        
                        <div id="actionBtnContainer" class="d-flex justify-content-center gap-3">
                            <button onclick="retakeQuiz()" class="btn btn-warning btn-lg px-4 fw-bold rounded-pill shadow-sm">
                                <i class="bi bi-arrow-clockwise me-2"></i>Ulangi Kuis
                            </button>
                            @if($hasilLama && $hasilLama->score >= 80)
                                <a href="{{ route('materi.index') }}" class="btn btn-dark btn-lg px-4 fw-bold rounded-pill shadow-sm">
                                    Lanjut Materi
                                </a>
                            @endif
                        </div>

                        <div id="reviewSection" class="text-start mt-5 {{ $hasilLama ? '' : 'd-none' }}">
                            <hr class="my-5">
                            <h4 class="fw-bold text-danger mb-4"><i class="bi bi-exclamation-triangle me-2"></i>Review Jawaban Salah:</h4>
                            <div id="wrongAnswersList">
                                {{-- Jika ada hasil lama tapi user mau lihat pembahasan kuis sebelumnya (Opsional) --}}
                            </div>
                        </div>
                    </div>

                    {{-- 2. FORM KUIS --}}
                    <form id="quizExecutionForm" class="{{ $hasilLama ? 'd-none' : '' }}">
                        @foreach($materi->quizzes as $index => $quiz)
                            <div class="quiz-item mb-5 p-4 rounded-4 border bg-light" 
                                 data-correct="{{ $quiz->correct_answer }}">
                                
                                {{-- Simpan penjelasan di dalam div tersembunyi agar HTML-nya tidak rusak --}}
                                <div class="d-none explanation-source">{!! $quiz->explanation !!}</div>
                                
                                <h6 class="fw-bold text-dark mb-3">
                                    <span class="badge bg-dark me-2">{{ $index + 1 }}</span> 
                                    <span class="question-text">{{ $quiz->question_text }}</span>
                                </h6>
                                
                                <div class="options-list d-flex flex-column gap-2">
                                    @foreach(['a', 'b', 'c', 'd'] as $opt)
                                        <label class="option-card p-3 rounded-3 border bg-white shadow-sm" style="cursor: pointer;">
                                            <input type="radio" name="answer_{{ $quiz->id }}" value="{{ $opt }}" class="me-2" required>
                                            <span class="fw-bold me-2">{{ strtoupper($opt) }}.</span> 
                                            <span class="opt-text">{{ $quiz->{'option_'.$opt} }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-5">
                            <button type="button" onclick="calculateScore()" class="btn btn-primary btn-lg px-5 fw-bold rounded-pill shadow">
                                <i class="bi bi-send-check me-2"></i>Kumpulkan Jawaban
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS Quill Snow --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script>
function retakeQuiz() {
    document.getElementById('resultSection').classList.add('d-none');
    document.getElementById('quizExecutionForm').classList.remove('d-none');
    document.getElementById('quizExecutionForm').reset();
}

function calculateScore() {
    const quizItems = document.querySelectorAll('.quiz-item');
    let totalQuestions = quizItems.length;
    let correctCount = 0;
    let answeredAll = true;
    let wrongAnswersHtml = '';

    quizItems.forEach((item, index) => {
        const selected = item.querySelector('input[type="radio"]:checked');
        const correctAnswer = item.getAttribute('data-correct').toLowerCase();
        
        // AMBIL HTML DARI DIV TERSEMBUNYI (PENTING!)
        const explanationHtml = item.querySelector('.explanation-source').innerHTML;
        const questionText = item.querySelector('.question-text').innerText;

        if (!selected) {
            answeredAll = false;
            return;
        }
        
        if (selected.value === correctAnswer) {
            correctCount++;
        } else {
            const userAnsText = item.querySelector(`input[value="${selected.value}"]`).parentElement.querySelector('.opt-text').innerText;
            const correctAnsText = item.querySelector(`input[value="${correctAnswer}"]`).parentElement.querySelector('.opt-text').innerText;

            wrongAnswersHtml += `
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden border-start border-danger border-4">
                    <div class="card-header bg-white fw-bold py-3">Nomor ${index + 1} (Salah)</div>
                    <div class="card-body bg-white p-4">
                        <p class="fw-bold mb-3">${questionText}</p>
                        <div class="small mb-3">
                            <div class="text-danger mb-1"> Jawaban Kamu: <strong>(${selected.value.toUpperCase()}) ${userAnsText}</strong></div>
                            <div class="text-success"> Jawaban Benar: <strong>(${correctAnswer.toUpperCase()}) ${correctAnsText}</strong></div>
                        </div>
                        <div class="p-3 bg-light rounded-3">
                            <strong class="text-primary small d-block mb-2">PEMBAHASAN:</strong>
                            <div class="ql-editor p-0 text-dark small">
                                ${explanationHtml}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    });

    if (!answeredAll) {
        alert('Mohon jawab semua pertanyaan!');
        return;
    }

    const score = Math.round((correctCount / totalQuestions) * 100);

    document.getElementById('quizExecutionForm').classList.add('d-none');
    document.getElementById('resultSection').classList.remove('d-none');
    
    const finalScoreEl = document.getElementById('finalScore');
    const scoreMsg = document.getElementById('scoreMessage');
    const actionContainer = document.getElementById('actionBtnContainer');
    const reviewSection = document.getElementById('reviewSection');
    const wrongAnswersList = document.getElementById('wrongAnswersList');

    finalScoreEl.innerText = score;
    wrongAnswersList.innerHTML = wrongAnswersHtml;

    if (score >= 80) {
        finalScoreEl.className = "display-1 fw-bold text-success";
        scoreMsg.innerHTML = "Selamat! Kamu Lulus.";
        actionContainer.innerHTML = `
            <form action="{{ route('quiz.done', $materi->id) }}" method="POST">
                @csrf
                <input type="hidden" name="score" value="${score}">
                <button type="submit" class="btn btn-success btn-lg px-5 fw-bold rounded-pill shadow">Selesaikan Materi & Lanjut</button>
            </form>
        `;
        if(wrongAnswersHtml !== '') reviewSection.classList.remove('d-none');
        else reviewSection.classList.add('d-none');
    } else {
        finalScoreEl.className = "display-1 fw-bold text-danger";
        scoreMsg.innerHTML = "Skor kamu belum mencapai 80. Silakan ulangi kuis.";
        actionContainer.innerHTML = `<button onclick="retakeQuiz()" class="btn btn-warning btn-lg px-5 fw-bold rounded-pill shadow">Ulangi Kuis</button>`;
        reviewSection.classList.remove('d-none');
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<style>
    /* Styling Review agar mengikuti standar Quill */
    .ql-editor { 
        height: auto !important; 
        padding: 0 !important; 
        font-family: inherit !important;
        overflow-y: visible !important;
    }
    .option-card:hover { background-color: #f8f9fa !important; border-color: #0d6efd !important; }
    .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .option-card { transition: all 0.2s ease; border: 1px solid #dee2e6 !important; }
</style>
@endsection