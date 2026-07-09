<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Resepku</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="login-card">
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <h2 style="color: #F5DEB3;">Lupa Password</h2>
            <p class="subtitle">Masukkan email akun Anda, kami akan bantu reset password-nya</p>

            @if ($errors->any())
                <div style="color: #d9534f; background-color: #fdf7f7; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="name@example.com" value="{{ old('email') }}">
            </div>

            <button type="submit">Lanjutkan</button>

            <p class="footer-text">
                <a href="{{ route('login') }}">Kembali ke halaman login</a>
            </p>
        </form>
    </div>

</body>

</html>
