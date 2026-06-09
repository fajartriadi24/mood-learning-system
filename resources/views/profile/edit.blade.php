@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="glass-box p-4 p-md-5 shadow-lg bg-white border-0">
                {{-- HEADER AREA --}}
                <div class="text-center mb-5">
                    <div class="profile-avatar mx-auto mb-3 shadow-sm animate-pulse">
                        <span class="fs-1">👤</span>
                    </div>
                    <h3 class="fw-bold text-dark m-0">Pengaturan Akun</h3>
                    <p class="text-muted small">Kelola identitas dan keamanan <strong>{{ $user->name }}</strong></p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm py-3 text-center">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('patch')

                    {{-- SECTION: IDENTITAS --}}
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge-icon me-2"><i class="bi bi-person-lines-fill"></i></div>
                            <h6 class="fw-bold text-dark m-0">Identitas Diri</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="label-custom">Nama Lengkap</label>
                                <div class="input-group-custom">
                                    <span class="input-icon"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" required>
                                </div>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="label-custom">Alamat Email</label>
                                <div class="input-group-custom">
                                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                </div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: KEAMANAN --}}
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge-icon me-2 text-primary"><i class="bi bi-shield-lock-fill"></i></div>
                            <h6 class="fw-bold text-dark m-0">Keamanan Password</h6>
                        </div>
                        
                        <div class="mb-4">
                            <label class="label-custom">Password Saat Ini</label>
                            <div class="input-group-custom password-toggle">
                                <span class="input-icon"><i class="bi bi-key"></i></span>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin ganti">
                                <span class="toggle-icon"><i class="bi bi-eye-slash togglePassword"></i></span>
                            </div>
                            @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="label-custom">Password Baru</label>
                                <div class="input-group-custom password-toggle">
                                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                                    <span class="toggle-icon"><i class="bi bi-eye-slash togglePassword"></i></span>
                                </div>
                                @error('new_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="label-custom">Konfirmasi Password Baru</label>
                                <div class="input-group-custom password-toggle">
                                    <span class="input-icon"><i class="bi bi-lock-check"></i></span>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                    <span class="toggle-icon"><i class="bi bi-eye-slash togglePassword"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="d-flex flex-column flex-md-row gap-3">
                        <button type="submit" class="btn btn-dark px-5 py-3 rounded-4 fw-bold shadow-sm flex-grow-1">
                            <i class="bi bi-save2 me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-5 py-3 rounded-4 fw-bold flex-grow-1">
                            Kembali
                        </a>
                    </div>
                </form>

                <div class="text-center mt-5 pt-4 border-top">
                    <button type="button" class="btn btn-link text-danger text-decoration-none small fw-bold" data-bs-toggle="modal" data-bs-target="#confirmDelete">
                        <i class="bi bi-exclamation-triangle me-1"></i> Ingin menghapus akun?
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div class="modal fade" id="confirmDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.destroy') }}" method="POST" class="p-4 p-md-5 pt-0">
                @csrf
                @method('delete')
                <div class="text-center">
                    <div class="fs-1 text-danger mb-3"><i class="bi bi-trash3"></i></div>
                    <h5 class="fw-bold text-dark mb-2">Penghapusan Akun</h5>
                    <p class="small text-muted mb-4">Aksi ini permanen. Masukkan password kamu untuk memverifikasi.</p>
                </div>
                
                <input type="password" name="password" class="form-control form-control-lg text-center mb-4" placeholder="Password Kamu" required>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger rounded-4 py-3 fw-bold">Ya, Hapus Permanen</button>
                    <button type="button" class="btn btn-light rounded-4 py-3" data-bs-dismiss="modal">Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JAVASCRIPT: TOGGLE PASSWORD --}}
<script>
document.querySelectorAll('.togglePassword').forEach(icon => {
    icon.addEventListener('click', function() {
        const input = this.closest('.input-group-custom').querySelector('input');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
});
</script>

<style>
    .glass-box { 
        border-radius: 40px; 
        background: #ffffff !important;
        border: 1px solid #f0f0f0;
    }
    
    .profile-avatar {
        width: 80px; height: 80px; background: #f8f9fa;
        border-radius: 30px; display: flex; align-items: center; justify-content: center;
    }

    .badge-icon {
        width: 35px; height: 35px; background: rgba(0,0,0,0.05);
        border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }

    .label-custom { font-size: 0.85rem; font-weight: 700; color: #6c757d; margin-bottom: 8px; margin-left: 5px; }

    /* CUSTOM INPUT DESIGN */
    .input-group-custom { position: relative; display: flex; align-items: center; }
    .input-icon { position: absolute; left: 18px; color: #adb5bd; z-index: 5; }
    .toggle-icon { position: absolute; right: 18px; color: #adb5bd; cursor: pointer; z-index: 5; }
    .toggle-icon:hover { color: #000; }

    .form-control {
        border-radius: 20px !important;
        padding: 14px 14px 14px 50px !important; /* Ruang untuk icon kiri */
        background: #f8f9fa !important;
        border: 2px solid transparent !important;
        transition: all 0.2s ease;
    }

    .password-toggle .form-control {
        padding-right: 50px !important; /* Ruang untuk icon mata */
    }

    .form-control:focus {
        background: #fff !important;
        border-color: #000 !important;
        box-shadow: none !important;
    }

    .btn-dark { background: #000; border: none; transition: 0.3s; }
    .btn-dark:hover { background: #333; transform: translateY(-2px); }

    .modal-backdrop { display: none !important; }
    .modal { background: rgba(0,0,0,0.4) !important; backdrop-filter: blur(5px); }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .animate-pulse { animation: pulse 3s infinite ease-in-out; }
</style>
@endsection