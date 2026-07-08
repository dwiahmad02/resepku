@extends('layouts.app')

@section('title', $resep->judul)

@push('styles')
<style>
    /* ── Hero Image ───────────────────────────────────────────────────── */
    .hero-wrap {
        padding: 32px 0 0;
    }

    .hero-img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 18px;
        display: block;
    }

    .hero-img-placeholder {
        width: 100%;
        height: 400px;
        border-radius: 18px;
        background: var(--cream-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 80px;
        color: var(--text-muted);
    }

    /* ── Meta Section ────────────────────────────────────────────────── */
    .meta-section {
        text-align: center;
        padding: 28px 0 0;
    }

    .recipe-title {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 12px;
        line-height: 1.25;
    }

    .category-badge {
        display: inline-block;
        background: v-bind;
        padding: 4px 18px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 14px;
    }

    .rating-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 20px;
        color: var(--text-muted);
        font-size: 14px;
    }

    .rating-row .star  { color: var(--gold); font-size: 17px; }
    .rating-row .score { font-weight: 700; color: var(--text-dark); font-size: 16px; }
    .rating-row .dot   { color: var(--border); }

    /* ── Action Buttons ──────────────────────────────────────────────── */
    .action-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1.5px solid var(--border);
        background: var(--white);
        color: var(--text-mid);
        border-radius: 24px;
        padding: 9px 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    .btn-action:hover { border-color: var(--terracotta); color: var(--terracotta); }

    .btn-like { border-color: #F0C4B4; color: var(--terracotta); }
    .btn-like.liked { background: #FEF0EA; border-color: var(--terracotta); }
    .btn-like:hover { background: #FEF0EA; }

    /* ── Divider ─────────────────────────────────────────────────────── */
    .section-divider {
        border: none;
        border-top: 1px solid var(--border);
        margin: 28px 0;
    }

    /* ── Info Stats ──────────────────────────────────────────────────── */
    .info-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 18px 14px;
        text-align: center;
    }

    .stat-icon  { font-size: 24px; margin-bottom: 6px; }
    .stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; font-weight: 500; }
    .stat-value { font-size: 16px; font-weight: 700; color: var(--text-dark); margin-top: 4px; }

    /* ── Section Cards ───────────────────────────────────────────────── */
    .section-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 24px 26px;
        margin-bottom: 18px;
    }

    .section-heading {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        border-left: 4px solid var(--terracotta);
        padding-left: 12px;
        margin-bottom: 16px;
        line-height: 1.3;
    }

    .desc-text {
        font-size: 15px;
        color: var(--text-mid);
        line-height: 1.75;
    }

    /* ── Two Column ──────────────────────────────────────────────────── */
    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 18px;
    }

    /* ── Bahan List ──────────────────────────────────────────────────── */
    .bahan-list {
        list-style: none;
    }

    .bahan-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14px;
        color: var(--text-mid);
        padding: 8px 0;
        border-bottom: 1px solid #F2EDE7;
        line-height: 1.5;
    }
    .bahan-list li:last-child { border-bottom: none; }

    .bahan-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--terracotta);
        flex-shrink: 0;
        margin-top: 6px;
    }

    /* ── Langkah List ────────────────────────────────────────────────── */
    .step-list { list-style: none; }

    .step-list li {
        display: flex;
        gap: 12px;
        font-size: 14px;
        color: var(--text-mid);
        padding: 10px 0;
        border-bottom: 1px solid #F2EDE7;
        line-height: 1.6;
    }
    .step-list li:last-child { border-bottom: none; }

    .step-num {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--terracotta);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ── Tips Box ────────────────────────────────────────────────────── */
    .tips-box {
        background: var(--terra-bg);
        border: 1px solid var(--terra-border);
        border-radius: var(--radius-md);
        padding: 18px 22px;
        margin-bottom: 18px;
    }
    .tips-title { font-size: 14px; font-weight: 700; color: #8B5E00; margin-bottom: 8px; }
    .tips-text  { font-size: 14px; color: #7A5200; line-height: 1.65; }

    /* ── Resep Lain (sidebar bawah) ──────────────────────────────────── */
    .resep-lain-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 10px;
    }

    .resep-card-mini {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s, border-color 0.2s;
        display: block;
    }
    .resep-card-mini:hover { transform: translateY(-3px); border-color: var(--terracotta); }

    .resep-card-mini img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        display: block;
    }

    .resep-card-mini-body { padding: 10px 12px 14px; }

    .resep-card-mini-cat {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--terracotta);
        margin-bottom: 4px;
    }

    .resep-card-mini-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.4;
    }

    /* ── Penulis Chip ────────────────────────────────────────────────── */
    .author-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--cream-dark);
        border-radius: 20px;
        padding: 4px 12px 4px 6px;
        font-size: 13px;
        color: var(--text-mid);
    }
    .author-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--terracotta);
        color: white;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ── Responsive ──────────────────────────────────────────────────── */
    @media (max-width: 640px) {
        .recipe-title    { font-size: 26px; }
        .hero-img,
        .hero-img-placeholder { height: 240px; }
        .two-col         { grid-template-columns: 1fr; }
        .info-stats      { grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .resep-lain-grid { grid-template-columns: 1fr; }
        .navbar-brand    { font-size: 18px; }
    }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- ── HERO IMAGE ───────────────────────────────────────────────────────── --}}
    <div class="hero-wrap">
        @if ($resep->foto)
            <img
                src="{{ asset('storage/' . $resep->foto) }}"
                alt="Foto {{ $resep->judul }}"
                class="hero-img"
            >
        @else
            <div class="hero-img-placeholder" aria-hidden="true">🍽️</div>
        @endif
    </div>

    {{-- ── META ────────────────────────────────────────────────────────────── --}}
    <div class="meta-section">

        <h1 class="recipe-title">{{ $resep->judul }}</h1>

        <span
            class="category-badge"
            style="background: {{ $resep->kategoriBadgeColor() }}; color: {{ $resep->kategoriBadgeText() }};"
        >
            {{ $resep->kategori }}
        </span>

        <div class="rating-row">
            <span class="star">★</span>
            <span class="score">{{ number_format($resep->rating, 1) }}</span>
            <span class="dot">·</span>
            <div class="author-chip">
                <div class="author-avatar">
                    {{ strtoupper(substr($resep->user->name, 0, 1)) }}
                </div>
                {{ $resep->user->name }}
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-row">

            {{-- Like --}}
            <button
                class="btn-action btn-like {{ $sudahLike ? 'liked' : '' }}"
                id="btn-like"
                data-id="{{ $resep->id }}"
                onclick="toggleLike(this)"
                aria-label="Suka resep ini"
            >
                <span id="like-icon">{{ $sudahLike ? '❤️' : '🤍' }}</span>
                <span id="like-count">{{ $resep->likes }}</span>
            </button>

            {{-- Share --}}
            <button class="btn-action" onclick="salinLink()" aria-label="Salin tautan resep">
                🔗 Bagikan
            </button>

            {{-- Edit (hanya pemilik) --}}
            @auth
                @if (Auth::id() === $resep->user_id)
                    <a href="{{ route('resep.edit', $resep->id) }}" class="btn-action">
                        ✏️ Edit
                    </a>
                @endif
            @endauth

        </div>
        <p id="share-msg" style="font-size:13px; color:var(--terracotta); display:none; margin-top:8px;">
            Tautan berhasil disalin! ✓
        </p>

    </div>

    <hr class="section-divider">

    {{-- ── INFO STATS ───────────────────────────────────────────────────────── --}}
    <div class="info-stats">
        <div class="stat-card">
            <div class="stat-icon">⏱️</div>
            <div class="stat-label">Waktu Masak</div>
            <div class="stat-value">
                @if ($resep->durasi_menit >= 60)
                    {{ floor($resep->durasi_menit / 60) }} jam
                    @if ($resep->durasi_menit % 60 > 0)
                        {{ $resep->durasi_menit % 60 }} mnt
                    @endif
                @else
                    {{ $resep->durasi_menit }} menit
                @endif
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🍽️</div>
            <div class="stat-label">Porsi</div>
            <div class="stat-value">{{ $resep->porsi }} orang</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-label">Kesulitan</div>
            <div class="stat-value">{{ $resep->kesulitanEmoji() }}</div>
        </div>
    </div>

    {{-- ── DESKRIPSI ────────────────────────────────────────────────────────── --}}
    <div class="section-card">
        <h2 class="section-heading">Deskripsi</h2>
        <p class="desc-text">{{ $resep->deskripsi }}</p>
    </div>

    {{-- ── BAHAN + LANGKAH ──────────────────────────────────────────────────── --}}
    <div class="two-col">

        <div class="section-card">
            <h2 class="section-heading">Bahan-Bahan</h2>
            <ul class="bahan-list">
                @foreach ($resep->bahan as $bahan)
                    <li>
                        <span class="bahan-dot" aria-hidden="true"></span>
                        {{ $bahan }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="section-card">
            <h2 class="section-heading">Langkah Memasak</h2>
            <ol class="step-list">
                @foreach ($resep->langkah as $i => $langkah)
                    <li>
                        <span class="step-num" aria-hidden="true">{{ $i + 1 }}</span>
                        <span>{{ $langkah }}</span>
                    </li>
                @endforeach
            </ol>
        </div>

    </div>

    {{-- ── TIPS ─────────────────────────────────────────────────────────────── --}}
    @if ($resep->tips)
    <div class="tips-box">
        <p class="tips-title">💡 Tips dari Penulis</p>
        <p class="tips-text">{{ $resep->tips }}</p>
    </div>
    @endif

    {{-- ── RESEP LAIN DARI PENULIS ──────────────────────────────────────────── --}}
    @if ($resepLain->count() > 0)
    <div class="section-card">
        <h2 class="section-heading">Resep Lain dari {{ $resep->user->name }}</h2>
        <div class="resep-lain-grid">
            @foreach ($resepLain as $r)
            <a href="{{ route('resep.show', $r->slug) }}" class="resep-card-mini">
                @if ($r->foto)
                    <img src="{{ asset('storage/' . $r->foto) }}" alt="{{ $r->judul }}">
                @else
                    <div style="height:120px; background:var(--cream-dark); display:flex; align-items:center; justify-content:center; font-size:36px;">🍴</div>
                @endif
                <div class="resep-card-mini-body">
                    <p class="resep-card-mini-cat">{{ $r->kategori }}</p>
                    <p class="resep-card-mini-title">{{ $r->judul }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>{{-- .main-content --}}
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ── Toggle Like ───────────────────────────────────────────────────────────
    async function toggleLike(btn) {
        const resepId = btn.dataset.id;

        try {
            const res = await fetch(`/resep/${resepId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            if (res.status === 401) {
                window.location.href = '/login';
                return;
            }

            const data = await res.json();
            document.getElementById('like-icon').textContent  = data.liked ? '❤️' : '🤍';
            document.getElementById('like-count').textContent = data.likes;
            btn.classList.toggle('liked', data.liked);

        } catch (err) {
            console.error('Gagal memperbarui like:', err);
        }
    }

    // ── Salin Link ────────────────────────────────────────────────────────────
    async function salinLink() {
        try {
            await navigator.clipboard.writeText(window.location.href);
            const msg = document.getElementById('share-msg');
            msg.style.display = 'block';
            setTimeout(() => { msg.style.display = 'none'; }, 2500);
        } catch (err) {
            alert('Tautan: ' + window.location.href);
        }
    }
</script>
@endpush
