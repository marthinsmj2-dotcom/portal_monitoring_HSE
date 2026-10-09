<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - {{ config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
<div class="login-wrap">
    <div class="login-card">

        <div class="text-center mb-4">
            <div class="brand-logo mx-auto mb-3">DHJ</div>
            <h4 class="fw-bold mb-1">Masuk ke HSE System</h4>
            <p class="text-muted mb-0">PT. Duta Hita Jaya</p>
        </div>

        {{-- Pesan setelah logout --}}
        @if (session('status'))
            <div class="alert alert-success py-2 small">
                <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" novalidate>
            @csrf

            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" autofocus autocomplete="username">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-brand w-100">Masuk</button>
        </form>

        <div class="login-hint">
            Akun demo: <strong>admin</strong> / <strong>admin123</strong>
        </div>
    </div>
</div>
</body>
</html>