<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materi; 
use App\Models\Progress; 
use App\Models\Quiz; 
use App\Models\QuizResult; 
use Illuminate\Support\Facades\Storage; 

class MateriController extends Controller
{
    /**
     * Menampilkan halaman materi untuk SISWA (Dinamis berdasarkan Mood)
     */
    public function index()
    {
        // Mengambil mood dari session, default ke 'biasa' jika belum terdeteksi
        $mood = session('current_mood', 'biasa');

        $materis = Materi::with('user')
                    ->where('mood_category', $mood)
                    ->orderBy('created_at', 'asc') 
                    ->get();

        return view('materi.materi', [
            'mood' => $mood,
            'materis' => $materis 
        ]);
    }

    /**
     * Fitur Gamifikasi: Mencatat Video Selesai ditonton
     */
    public function markVideoDone(int $id)
    {
        Progress::updateOrCreate(
            ['user_id' => Auth::id(), 'materi_id' => $id],
            ['video_finished' => true]
        );

        return response()->json(['success' => true, 'message' => 'Video selesai!']);
    }

    /**
     * Mencatat Kuis Selesai dan Redirect ke Materi Selanjutnya
     */
    public function markQuizDone(Request $request, int $id)
    {
        // 1. Catat progres kuis di tabel Progress
        Progress::updateOrCreate(
            ['user_id' => Auth::id(), 'materi_id' => $id],
            ['quiz_finished' => true]
        );

        // 2. Simpan skor ke tabel QuizResult
        QuizResult::updateOrCreate(
            ['user_id' => Auth::id(), 'materi_id' => $id],
            ['score' => $request->score ?? 100]
        );

        return redirect()->route('materi.index')->with('success', 'Selamat! Kuis selesai.');
    }

    /**
     * Menampilkan halaman form UPLOAD untuk GURU
     */
    public function create()
    {
        return view('materi.create');
    }

    /**
     * Proses simpan materi (Mendukung Video Lokal & Link YouTube)
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'mood_category' => 'required|in:semangat,lelah,bingung,biasa',
            'video' => 'nullable|mimes:mp4,mov,ogg|max:102400', 
            'video_url' => 'nullable|url',
            'deskripsi' => 'nullable|string',
        ]);

        $videoPath = null;
        $url = $request->video_url;
        $videoId = "";

        // Jika upload file lokal
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
        }

        // Logic konversi link YouTube ke format Embed (Paling Aman)
        if ($url) {
            if (strpos($url, 'youtu.be/') !== false) {
                $videoId = substr($url, strrpos($url, '/') + 1);
            } elseif (strpos($url, 'v=') !== false) {
                parse_str(parse_url($url, PHP_URL_QUERY), $my_vars);
                $videoId = $my_vars['v'] ?? "";
            }
        }

        $finalVideoUrl = $videoId ? "https://www.youtube.com/embed/" . $videoId : $url;

        Materi::create([
            'user_id'       => Auth::id(), 
            'judul'         => $request->judul,
            'mood_category' => $request->mood_category,
            'video_path'    => $videoPath,
            'video_url'     => $finalVideoUrl,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->route('dashboard')->with('success', 'Materi Berhasil Diupload!');
    }

    /**
     * Form Edit Materi
     */
    public function edit(int $id)
    {
        $materi = Materi::where('user_id', Auth::id())->findOrFail($id);
        return view('materi.edit', compact('materi'));
    }

    /**
     * Update Materi
     */
    public function update(Request $request, int $id)
    {
        $materi = Materi::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'mood_category' => 'required|in:semangat,lelah,bingung,biasa',
            'video' => 'nullable|mimes:mp4,mov,ogg|max:102400',
            'video_url' => 'nullable|url',
            'deskripsi' => 'nullable|string',
        ]);

        $videoPath = $materi->video_path;
        $url = $request->video_url;
        $videoId = "";

        if ($request->hasFile('video')) {
            if ($materi->video_path) {
                Storage::disk('public')->delete($materi->video_path);
            }
            $videoPath = $request->file('video')->store('videos', 'public');
        }

        if ($url) {
            if (strpos($url, 'youtu.be/') !== false) {
                $videoId = substr($url, strrpos($url, '/') + 1);
            } elseif (strpos($url, 'v=') !== false) {
                parse_str(parse_url($url, PHP_URL_QUERY), $my_vars);
                $videoId = $my_vars['v'] ?? "";
            }
        }

        $finalVideoUrl = $videoId ? "https://www.youtube.com/embed/" . $videoId : $url;

        $materi->update([
            'judul' => $request->judul,
            'mood_category' => $request->mood_category,
            'deskripsi' => $request->deskripsi,
            'video_path' => $videoPath,
            'video_url' => $finalVideoUrl,
        ]);

        return redirect()->route('dashboard')->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Hapus Materi
     */
    public function destroy(int $id)
    {
        $materi = Materi::where('user_id', Auth::id())->findOrFail($id);
        
        if ($materi->video_path) {
            Storage::disk('public')->delete($materi->video_path);
        }
        
        $materi->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Menyimpan Kuis
     */
    public function storeQuiz(Request $request)
    {
        $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'questions' => 'required',
        ]);

        $questions = $request->input('questions');

        if (isset($questions['text'])) {
            $questions = [0 => $questions];
        }

        foreach ($questions as $q) {
            if (is_array($q)) {
                Quiz::create([
                    'materi_id'      => $request->materi_id,
                    'question_text'  => $q['text'],
                    'option_a'       => $q['a'],
                    'option_b'       => $q['b'],
                    'option_c'       => $q['c'],
                    'option_d'       => $q['d'],
                    'correct_answer' => $q['correct'],
                    'explanation'    => $q['explanation'] ?? null,
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Kuis berhasil dipublikasikan!');
    }

    /**
     * Menampilkan halaman kuis untuk siswa/guru
     */
    public function showQuiz(int $id)
    {
        $materi = Materi::with('quizzes')->findOrFail($id);
        return view('quiz.show', compact('materi'));
    }

    /**
     * Menghapus semua kuis pada suatu materi
     */
    public function destroyQuiz(int $id)
    {
        Quiz::where('materi_id', $id)->delete();
        return redirect()->back()->with('success', 'Kuis berhasil dihapus!');
    }

    /**
     * Menampilkan halaman edit kuis
     */
    public function editQuiz(int $id)
    {
        $materi = Materi::with('quizzes')->findOrFail($id);
        return view('quiz.edit', compact('materi'));
    }

    /**
     * Proses memperbarui soal-soal kuis
     */
    public function updateQuiz(Request $request, int $id)
    {
        $request->validate([
            'questions' => 'required|array',
        ]);

        Quiz::where('materi_id', $id)->delete();

        foreach ($request->questions as $q) {
            Quiz::create([
                'materi_id'      => $id,
                'question_text'  => $q['text'],
                'option_a'       => $q['a'],
                'option_b'       => $q['b'],
                'option_c'       => $q['c'],
                'option_d'       => $q['d'],
                'correct_answer' => $q['correct'],
                'explanation'    => $q['explanation'] ?? null,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Soal kuis berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman form pembuatan kuis
     */
    public function createQuiz()
    {
        $materis = Materi::where('user_id', Auth::id())->get();
        return view('quiz.create', compact('materis'));
    }
}