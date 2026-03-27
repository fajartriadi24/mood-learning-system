<!DOCTYPE html>
<html>
<head>
    <title>Mood Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">Mood Learning</span>
        <div>
            <a href="/dashboard" class="btn btn-light btn-sm">Dashboard</a>
            <a href="/mood" class="btn btn-light btn-sm">Mood</a>
            <a href="/materi" class="btn btn-light btn-sm">Materi</a>
            <a href="/quiz" class="btn btn-light btn-sm">Quiz</a>
            <a href="/history" class="btn btn-light btn-sm">History</a>
            <a href="/profile" class="btn btn-light btn-sm">Profile</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

</body>
</html>