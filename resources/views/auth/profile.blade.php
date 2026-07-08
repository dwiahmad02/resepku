<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Resepku</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=2.0">
    <style>
        /* =========================================
           PROFILE — extends style.css tokens
        ========================================= */

        .profile-wrap {
            max-width: 760px;
            margin: 48px auto;
            padding: 0 5%;
        }

        /* AVATAR CARD */
        .profile-avatar-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 28px 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 20px;
        }

        .avatar-ring {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid var(--brand);
            overflow: hidden;
            flex-shrink: 0;
            background: var(--surface-2);
        }

        .avatar-ring img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-meta h2 {
            font-family: 'Lora', serif;
            font-size: 1.4rem;
            color: var(--text);
            margin-bottom: 4px;
        }

        .profile-meta p {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin: 2px 0;
        }


        /* TABS */
        .tab-nav {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab-btn {
            background: var(--surface);
            color: var(--brand);
            border: 1.5px solid var(--border);
            padding: 8px 20px;
            border-radius: var(--radius-pill);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all var(--transition);
            white-space: nowrap;
        }

        .tab-btn:hover {
            background: var(--brand-light);
            border-color: var(--brand);
        }

        .tab-btn.active {
            background: var(--brand);
            color: #fff;
            border-color: var(--brand);
            box-shadow: 0 4px 12px rgba(192,81,10,.3);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* CARD GENERIC */
        .profile-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 28px 24px;
        }

        .profile-card h3 {
            font-family: 'Lora', serif;
            font-size: 1.15rem;
            color: var(--text);
            margin-bottom: 20px;
        }

        /* FORM */
        .form-group { margin-bottom: 16px; }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.93rem;
            background: var(--surface);
            color: var(--text);
            box-sizing: border-box;
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(192,81,10,.1);
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 90px;
        }

        .form-group .hint {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .btn-submit {
            background: var(--brand);
            color: #fff;
            border: none;
            padding: 10px 26px;
            border-radius: var(--radius-pill);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(192,81,10,.3);
            transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
        }

        .btn-submit:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(192,81,10,.4);
        }

        /* ALERT */
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .alert-error {
            background: #fff5f5;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        /* FOTO PREVIEW */
        #foto-preview {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
            border: 2px solid var(--brand);
            display: none;
        }

        /* RESEP TERSIMPAN */
        .resep-grid-profile {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .resep-card-sm {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .resep-card-sm:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .resep-card-sm .img-wrapper {
            height: 110px;
        }

        .resep-card-sm-info {
            padding: 10px 12px;
        }

        .resep-card-sm-info strong {
            display: block;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .resep-card-sm-info p {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin: 1px 0;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .empty-state .empty-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: block;
        }

        @media (max-width: 640px) {
            .profile-avatar-card { flex-direction: column; text-align: center; }
            .resep-grid-profile { grid-template-columns: 1fr 1fr; }
            .tab-btn { font-size: 0.8rem; padding: 7px 14px; }
        }
    </style>
</head>
<body>

{{-- ===== NAVBAR (identik dengan landingpage) ===== --}}
<nav class="navbar">
    <a href="{{ route('home') }}" class="logo">Resepku</a>
    <div class="nav-links">
        <a href="{{ route('home') }}"            class="nav-link">Home</a>
        <a href="{{ route('resep.dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('profile') }}"         class="nav-link active">Profile</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link" style="background:none;border:none;font:inherit;cursor:pointer;padding:0;">Logout</button>
        </form>
        <button type="button" class="btn-icon" id="darkMode" title="Ganti tema">🌙</button>
    </div>
</nav>

{{-- Dark Mode Script ===== --}}
<script>
    (function () {
        var btn = document.getElementById('darkMode');
        if (!btn) return;
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            btn.textContent = '☀️';
        }
        btn.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            var isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            btn.textContent = isDark ? '☀️' : '🌙';
        });
    }());
</script>

<div class="profile-wrap">

    {{-- AVATAR CARD --}}
    <div class="profile-avatar-card">
        <div class="avatar-ring">
            <img src="{{ $profil && $profil->profile_pict ? asset('uploads/profil/' . $profil->profile_pict) : '' }}"
                 alt="Foto Profil"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=c0510a&color=fff&size=90&bold=true'">
        </div>
        <div class="profile-meta">
            <h2>Hi, {{ $user->username }}!</h2>
            <p>✉️ {{ $user->email }}</p>
            @if($profil && $profil->nama_lengkap)
                <p>👤 {{ $profil->nama_lengkap }}</p>
            @endif

        </div>
    </div>

    {{-- TABS --}}
    <div class="tab-nav">
        <button class="tab-btn active" onclick="showTab('resep', this)">❤️ Resep Tersimpan</button>
        <button class="tab-btn"        onclick="showTab('edit', this)">✏️ Edit Profil</button>
        <button class="tab-btn"        onclick="showTab('password', this)">🔒 Ganti Password</button>
    </div>

    {{-- TAB: RESEP TERSIMPAN --}}
    <div id="tab-resep" class="tab-content active">
        <div class="profile-card">
            <h3>❤️ Resep Tersimpan <span style="font-size:0.9rem;font-weight:500;color:var(--text-muted);">({{ count($resep_tersimpan) }})</span></h3>
            @if($resep_tersimpan->isEmpty())
                <div class="empty-state">
                    <span class="empty-icon">📭</span>
                    <p>Belum ada resep yang disimpan.<br>Jelajahi resep dan simpan favoritmu!</p>
                </div>
            @else
                <div class="resep-grid-profile">
                    @foreach($resep_tersimpan as $resep)
                        <a href="{{ route('resep.detail', $resep->id_resep) }}" class="resep-card-sm">
                            <div class="img-wrapper">
                                <img src="{{ asset('uploads/' . $resep->foto) }}"
                                     alt="{{ $resep->nama_masakan }}"
                                     onerror="this.src='{{ asset('img/default.jpg') }}'">
                            </div>
                            <div class="resep-card-sm-info">
                                <strong>{{ $resep->nama_masakan }}</strong>
                                <p>👨‍🍳 {{ $resep->nama_chef }}</p>
                                <p>⭐ {{ number_format($resep->rating, 1) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- TAB: EDIT PROFIL --}}
    <div id="tab-edit" class="tab-content">
        <div class="profile-card">
            <h3>✏️ Edit Profil</h3>

            @if(session('status'))
                <div class="alert-success">✅ {{ session('status') }}</div>
            @endif
            @error('foto') <div class="alert-error">{{ $message }}</div> @enderror
            @error('nama_lengkap') <div class="alert-error">{{ $message }}</div> @enderror

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="foto" accept="image/*" onchange="previewFoto(this)">
                    <img id="foto-preview" src="" alt="Preview">
                    <p class="hint">Maks. 2MB · Format: JPG, PNG, WEBP</p>
                </div>

                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap"
                           value="{{ old('nama_lengkap', $profil->nama_lengkap ?? '') }}"
                           placeholder="Masukkan nama lengkap">
                </div>


                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    {{-- TAB: GANTI PASSWORD --}}
    <div id="tab-password" class="tab-content">
        <div class="profile-card">
            <h3>🔒 Ganti Password</h3>

            @if(session('status_password'))
                <div class="alert-success">✅ {{ session('status_password') }}</div>
            @endif
            @error('password_lama')   <div class="alert-error">{{ $message }}</div> @enderror
            @error('password_baru')   <div class="alert-error">{{ $message }}</div> @enderror
            @error('konfirmasi_baru') <div class="alert-error">{{ $message }}</div> @enderror

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="password_lama">Password Lama</label>
                    <input type="password" id="password_lama" name="password_lama"
                           placeholder="Masukkan password lama">
                </div>

                <div class="form-group">
                    <label for="password_baru">Password Baru</label>
                    <input type="password" id="password_baru" name="password_baru"
                           placeholder="Minimal 6 karakter">
                </div>

                <div class="form-group">
                    <label for="konfirmasi_baru">Konfirmasi Password Baru</label>
                    <input type="password" id="konfirmasi_baru" name="konfirmasi_baru"
                           placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn-submit">Ubah Password</button>
            </form>
        </div>
    </div>

</div>{{-- end .profile-wrap --}}

<script>
    function showTab(tab, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        btn.classList.add('active');
    }

    function previewFoto(input) {
        const preview = document.getElementById('foto-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Buka tab yang sesuai setelah redirect
    @if(session('status'))
        document.querySelectorAll('.tab-btn')[1].click();
    @endif
    @if(session('status_password') || $errors->has('password_lama') || $errors->has('password_baru') || $errors->has('konfirmasi_baru'))
        document.querySelectorAll('.tab-btn')[2].click();
    @endif
    @if($errors->has('nama_lengkap') || $errors->has('foto'))
        document.querySelectorAll('.tab-btn')[1].click();
    @endif
</script>

</body>
</html>