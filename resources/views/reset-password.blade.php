<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Resepku</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="login-card">
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <h2 style="color: #F5DEB3;">Buat Password Baru</h2>
            <p class="subtitle">Masukkan password baru untuk akun Anda</p>

            @if ($errors->any())
                <div style="color: #d9534f; background-color: #fdf7f7; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="input-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter">
            </div>

            <div class="input-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru">
            </div>

            <button type="submit">Simpan Password</button>

            <p class="footer-text">
                <a href="{{ route('login') }}">Batal, kembali ke login</a>
            </p>
        </form>
    </div>

</body>

</html>
