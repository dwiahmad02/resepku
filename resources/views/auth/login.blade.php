<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Resepku</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="login-card">
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <h2 style="color: #F5DEB3;">Login</h2>
            <p class="subtitle">Selamat datang kembali di Resepku</p>

            @if ($errors->any())
                <div style="color: #d9534f; background-color: #fdf7f7; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="name@example.com" value="{{ old('email') }}">
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="password-wrapper" style="position: relative;">
                    <input type="password" id="password" name="password" required placeholder="Masukkan password">
                    <button type="button" class="toggle-password" id="toggleBtn" title="Tampilkan/sembunyikan password" style="border: none; background: transparent; cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                        <svg id="eyeIcon" width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-options">
                <a href="#">Lupa password?</a>
            </div>

            <button type="submit">Masuk</button>

            <div class="divider">
                <span>atau</span>
            </div>

            <button type="button" class="google-btn">
                <img src="{{ asset('storage/google.png') }}" alt="Google Logo" onerror="this.style.display='none'">
                Masuk dengan Google
            </button>

            <p class="footer-text">
                Belum punya akun? <a href="{{ url('/register') }}">Daftar sekarang</a>
            </p>
        </form>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('toggleBtn');

        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        });
    </script>

</body>

</html>
