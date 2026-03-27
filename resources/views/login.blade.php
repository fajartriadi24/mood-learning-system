<!DOCTYPE html>
<html>
<head>
    <title>Login - Mood Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="d-flex justify-content-center align-items-center" style="height:100vh;">

<div class="glass p-4 text-white" style="width:350px;">
    
    <h3 class="text-center mb-3">Login</h3>

    <!-- FORM -->
    <form>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" placeholder="Masukkan email">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" placeholder="Masukkan password">
        </div>

        <a href="/mood" class="btn btn-custom w-100">Login</a>
    </form>

    <!-- REGISTER -->
    <div class="text-center mt-3">
        <a href="/register" class="text-white">Belum punya akun? Daftar</a>
    </div>

</div>

</body>
</html>