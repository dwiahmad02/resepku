<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ !empty($resep->nama_masakan) ? $resep->nama_masakan : $resep->nama_makanan }} — ResepKu</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=2.0">
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}?v=2.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">Resepku</a>
        <div class="nav-links">
            <a href="{{ route('home') }}"            class="nav-link active">Home</a>
            <a href="{{ route('resep.dashboard') }}" class="nav-link">Dashboard</a>
            @if (Auth::guard('tbuser')->check())
            @if (Auth::guard('tbuser')->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Panel</a>
            @endif
            <a href="{{ route('profile') }}" class="nav-link">Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link" style="background:none;border:none;font:inherit;cursor:pointer;padding:0;">Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="nav-link">Login</a>
            @endif
            <button type="button" class="btn-icon" id="darkMode" title="Ganti tema">🌙</button>
        </div>
    </nav>

    @php
        $foto_resep = !empty($resep->foto) ? $resep->foto : ($resep->gambar ?? '');
        $nama_resep = !empty($resep->nama_masakan) ? $resep->nama_masakan : $resep->nama_makanan;
    @endphp

    {{-- ===== HERO ===== --}}
    <div class="detail-hero">
        <img
            src="{{ asset('uploads/' . $foto_resep) }}"
            alt="{{ $nama_resep }}"
            class="detail-hero-img"
            onerror="this.onerror=null; this.src='{{ asset('img/default.jpg') }}';">

        <div class="detail-hero-overlay">
            <span class="category-label">{{ $resep->nama_kategori ?? 'Umum' }}</span>
            <h1 class="detail-title">{{ $nama_resep }}</h1>

            @if (!empty($resep->rating) || !empty($resep->nama_chef))
            <div class="detail-hero-meta">
                @if (!empty($resep->rating))
                    <span>⭐ {{ number_format($resep->rating, 1) }}</span>
                @endif
                @if (!empty($resep->rating) && !empty($resep->nama_chef))
                    <span class="meta-sep">·</span>
                @endif
                @if (!empty($resep->nama_chef))
                    <span>👨‍🍳 {{ $resep->nama_chef }}</span>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- ===== ACTION BAR ===== --}}
    <div class="detail-actions">
        <button type="button" id="likeBtn" class="btn-like"
            data-id="{{ $resep->id_resep }}"
            data-url="{{ route('resep.like') }}">
            <span id="likeIcon">🤍</span>
            <span id="likeCount">{{ $resep->suka ?? 0 }}</span> Suka
        </button>

        <button type="button" id="shareBtn" class="btn-share">
            <span id="shareIcon">🔗</span>
            <span id="shareText">Bagikan</span>
        </button>
    </div>

    {{-- ===== BREADCRUMB ===== --}}
    <a href="{{ route('home') }}" class="detail-back">← Kembali ke beranda</a>

    {{-- ===== KONTEN UTAMA ===== --}}
    <div class="detail-container">

        <div class="detail-card">
            <h2>📝 Deskripsi</h2>
            <p class="card-text">{{ $resep->deskripsi ?? 'Belum ada deskripsi.' }}</p>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <h2>🥗 Bahan-Bahan</h2>
                <p class="card-text">{{ $resep->bahan ?? 'Belum ada bahan-bahan.' }}</p>
            </div>
            <div class="detail-card">
                <h2>👨‍🍳 Langkah Memasak</h2>
                <p class="card-text">{{ $resep->langkah_masak ?? 'Belum ada langkah memasak.' }}</p>
            </div>
        </div>

        <div class="detail-card detail-comments">
            <h2>💬 Komentar & Ulasan</h2>

            @if (session('success'))
            <div class="detail-alert-success">
                ✅ {{ session('success') }}
            </div>
            @endif

            <div class="comment-form-wrapper">
                <form action="{{ route('resep.komentar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_resep" value="{{ $resep->id_resep }}">
                    <input type="text" name="username" placeholder="Nama Anda" required>
                    <textarea name="isi_komentar"
                        placeholder="Bagikan pengalamanmu memasak resep ini…"
                        required
                        rows="3"></textarea>
                    <button type="submit" class="btn-kirim-komen">Kirim Komentar</button>
                </form>
            </div>

            <div class="comment-list">
                @forelse ($komentar as $komen)
                <div class="comment-item">
                    <span class="comment-author">{{ $komen->username }}</span>
                    <p class="comment-text">{{ $komen->isi_komentar }}</p>
                </div>
                @empty
                <div class="comment-empty">
                    <span class="empty-icon">🍽️</span>
                    Belum ada ulasan. Jadilah yang pertama berkomentar!
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ===== JAVASCRIPT INLINE ===== --}}
    <script>
        // ── DARK MODE ──────────────────────────────────────────────
        (function () {
            var body = document.body;
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark-mode');
            }
            var btn = document.getElementById('darkMode');
            if (btn) {
                btn.innerHTML = body.classList.contains('dark-mode') ? '☀️' : '🌙';
                btn.onclick = function () {
                    var isDark = body.classList.toggle('dark-mode');
                    btn.innerHTML = isDark ? '☀️' : '🌙';
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                };
            }
        }());

        // ── LIKE ───────────────────────────────────────────────────
        (function () {
            var likeBtn   = document.getElementById('likeBtn');
            if (!likeBtn) return;

            var csrfMeta  = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfMeta ? csrfMeta.content : '';
            var likeUrl   = likeBtn.getAttribute('data-url');
            var likeCount = document.getElementById('likeCount');
            var icon      = document.getElementById('likeIcon');

            likeBtn.onclick = function () {
                var idResep = likeBtn.getAttribute('data-id');
                var isLiked = likeBtn.classList.contains('active');
                var action  = isLiked ? 'unlike' : 'like';
                var current = parseInt(likeCount ? likeCount.textContent : '0') || 0;

                // Optimistic update: UI langsung berubah
                if (action === 'like') {
                    likeBtn.classList.add('active');
                    if (icon)      icon.textContent      = '❤️';
                    if (likeCount) likeCount.textContent = current + 1;
                    likeBtn.style.color       = '#ff4757';
                    likeBtn.style.borderColor = '#ff4757';
                    likeBtn.style.background  = 'var(--surface)';
                } else {
                    likeBtn.classList.remove('active');
                    if (icon)      icon.textContent      = '🤍';
                    if (likeCount) likeCount.textContent = Math.max(0, current - 1);
                    likeBtn.style.color       = '';
                    likeBtn.style.borderColor = '';
                    likeBtn.style.background  = '';
                }

                // Sinkron ke backend
                fetch(likeUrl, {
                    method : 'POST',
                    headers: {
                        'Content-Type' : 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN' : csrfToken
                    },
                    body: 'id_resep=' + idResep + '&action=' + action
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.status === 'success' && likeCount) {
                        likeCount.textContent = data.new_count;
                    }
                })
                .catch(function (err) { console.error('Like error:', err); });
            };
        }());

        // ── SHARE ──────────────────────────────────────────────────
        (function () {
            var shareBtn  = document.getElementById('shareBtn');
            var shareText = document.getElementById('shareText');
            if (!shareBtn) return;

            var titleEl    = document.querySelector('.detail-title');
            var recipeName = titleEl ? titleEl.innerText : 'Masakan Lezat';

            shareBtn.onclick = async function () {
                var orig = shareText ? shareText.innerText : 'Bagikan';
                try {
                    if (navigator.share) {
                        await navigator.share({
                            title: 'Resep ' + recipeName,
                            text : 'Cek resep lezat ini di ResepKu!',
                            url  : window.location.href
                        });
                    } else if (navigator.clipboard) {
                        await navigator.clipboard.writeText(window.location.href);
                        if (shareText) shareText.innerText = 'Link Tersalin!';
                        shareBtn.classList.add('copied');
                        setTimeout(function () {
                            if (shareText) shareText.innerText = orig;
                            shareBtn.classList.remove('copied');
                        }, 2000);
                    } else {
                        window.prompt('Salin link berikut:', window.location.href);
                    }
                } catch (err) {
                    window.prompt('Salin link berikut:', window.location.href);
                }
            };
        }());
    </script>

</body>
</html>