<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $mood = $request->query('mood', 'biasa');

        // 1. LOGIKA KHUSUS GURU
        if ($user->role === 'guru') {
            // Ambil data siswa beserta relasinya untuk statistik guru
            $siswas = User::where('role', 'siswa')
                        ->with(['quizResults.materi', 'progress'])
                        ->get();

            // AMBIL SEMUA MATERI (Integrasi antar guru agar tidak tumpang tindih)
            // Kita gunakan with('user') supaya di tabel bisa muncul nama guru penguploadnya
            $allMateri = Materi::with('user')->latest()->get();

            $stats = [
                'total_materi' => $allMateri->count(), // Sekarang menghitung semua materi di sistem
                'total_siswa'  => $siswas->count(),
            ];

            // Tambahkan allMateri ke dalam compact
            return view('pages.dashboard', compact('mood', 'stats', 'siswas', 'allMateri'));
        }

        // 2. LOGIKA KHUSUS SISWA
        if ($user->role === 'siswa') {
            // Jika Siswa belum pilih mood di session, lempar ke halaman pilih mood
            if (!session()->has('current_mood')) {
                return redirect()->route('mood');
            }

            // Siswa tidak butuh data $siswas atau $stats materi guru
            return view('pages.dashboard', compact('mood'));
        }

        // 3. JAGA-JAGA (Jika role tidak terdefinisi)
        return redirect()->route('welcome')->with('error', 'Role tidak dikenali.');
    }

    /**
     * Menghapus data siswa dari sistem (Hanya untuk Guru)
     */
    public function destroySiswa($id)
    {
        // Pastikan yang menghapus adalah guru
        if (Auth::user()->role !== 'guru') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ini.');
        }

        // Cari user yang rolenya siswa agar tidak salah hapus
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        
        // Proses hapus
        $siswa->delete();

        // Kembali ke dashboard dengan pesan sukses
        return redirect()->back()->with('success', 'Data siswa berhasil dihapus!');
    }
}