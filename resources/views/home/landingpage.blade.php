<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=2.0">
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

    {{-- Dark Mode --}}
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

    {{-- ===== HERO ===== --}}
    <header class="hero">
        <h1>Temukan Resep<br>Lezat Favoritmu</h1>
        <p>Jutaan resep dari nusantara<br>mudah dicari dan mudah dipilih</p>

        <form action="{{ route('home') }}" method="GET" class="search-box">
            @if(!empty($id_filter_kategori))
            <input type="hidden" name="kategori" value="{{ $id_filter_kategori }}">
            @endif
            @if(!empty($sort) && $sort !== 'terbaru')
            <input type="hidden" name="sort" value="{{ $sort }}">
            @endif
            <input type="text" name="cari" placeholder="Cari masakan..." value="{{ $kata_kunci }}">
            <button type="submit" class="btn-primary">Cari</button>
        </form>
    </header>

    {{-- ===== KATEGORI ===== --}}
    <section class="categories">
        <h2>Kategori</h2>
        <div class="category-buttons">
            <a href="{{ route('home', array_filter(['cari' => $kata_kunci, 'sort' => $sort])) }}"
                class="btn-category {{ empty($id_filter_kategori) ? 'active' : '' }}">Semua</a>
            <a href="{{ route('home', array_filter(['kategori' => 1, 'cari' => $kata_kunci, 'sort' => $sort])) }}"
                class="btn-category {{ $id_filter_kategori == 1 ? 'active' : '' }}">Makanan Berat</a>
            <a href="{{ route('home', array_filter(['kategori' => 2, 'cari' => $kata_kunci, 'sort' => $sort])) }}"
                class="btn-category {{ $id_filter_kategori == 2 ? 'active' : '' }}">Makanan Ringan</a>
            <a href="{{ route('home', array_filter(['kategori' => 3, 'cari' => $kata_kunci, 'sort' => $sort])) }}"
                class="btn-category {{ $id_filter_kategori == 3 ? 'active' : '' }}">Dessert</a>
            <a href="{{ route('home', array_filter(['kategori' => 4, 'cari' => $kata_kunci, 'sort' => $sort])) }}"
                class="btn-category {{ $id_filter_kategori == 4 ? 'active' : '' }}">Minuman</a>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ===== SORT BAR ===== --}}
    <div class="sort-bar">
        <label for="sortSelect">Urutkan:</label>
        <form action="{{ route('home') }}" method="GET" id="sortForm">
            @if(!empty($kata_kunci))
            <input type="hidden" name="cari" value="{{ $kata_kunci }}">
            @endif
            @if(!empty($id_filter_kategori))
            <input type="hidden" name="kategori" value="{{ $id_filter_kategori }}">
            @endif
            <select name="sort" id="sortSelect" class="sort-select" onchange="document.getElementById('sortForm').submit()">
                <option value="terbaru"  {{ $sort == 'terbaru'   ? 'selected' : '' }}>Terbaru</option>
                <option value="rating"   {{ $sort == 'rating'    ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="nama_asc" {{ $sort == 'nama_asc'  ? 'selected' : '' }}>Nama A–Z</option>
                <option value="nama_desc"{{ $sort == 'nama_desc' ? 'selected' : '' }}>Nama Z–A</option>
            </select>
        </form>

        @if(!empty($kata_kunci))
        <span class="filter-active-badge">
            🔍 "{{ $kata_kunci }}"
            <a href="{{ route('home', array_filter(['kategori' => $id_filter_kategori, 'sort' => $sort])) }}" title="Hapus pencarian">✕</a>
        </span>
        @endif

        <span class="sort-info">{{ count($data_resep) }} resep ditemukan</span>
    </div>

    {{-- ===== SECTION HEADER ===== --}}
    <div class="section-header">
        <h2>Menu Resep</h2>
        <a href="{{ url('/tambah-resep') }}" class="btn-add">Tambah Resep</a>
    </div>

    {{-- ===== RECIPE GRID ===== --}}
    <section class="recipe-container">
        <div class="recipe-grid">
            @forelse ($data_resep as $row)
            <a href="{{ url('/resep/' . $row->id_resep) }}" class="recipe-link">
                <div class="recipe-card">
                    <div class="img-wrapper">
                        <img src="{{ asset('uploads/' . $row->gambar) }}"
                            alt="{{ $row->nama_makanan }}"
                            onerror="this.onerror=null; this.src='{{ asset('img/default.jpg') }}';">
                    </div>
                    <div class="card-content">
                        <span class="category-label">{{ $row->nama_kategori ?? 'Tanpa Kategori' }}</span>
                        <h3>{{ $row->nama_makanan }}</h3>
                        <p class="rating">⭐ {{ number_format($row->rating, 1) }} &nbsp;·&nbsp; {{ $row->nama_chef }}</p>
                    </div>
                </div>
            </a>

            @empty
            <div class="empty-state">
                <span class="empty-icon">🍽️</span>
                <p>Resep tidak ditemukan.<br>Coba kata kunci atau kategori lain.</p>
            </div>
            @endforelse
        </div>

    {{-- ===== FOOTER ===== --}}
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-brand">
                <span class="footer-logo">Resepku</span>
                <p>Jutaan resep dari nusantara, mudah dicari dan mudah dipilih.</p>
            </div>

            <div class="footer-links">
                <h4>Dibuat Oleh</h4>
                <a>Dwi Ahmad Maulana         - 20240801118</a>
                <a>Lucas Gabriel Mahatan     - 20240801012</a>
                <a>Stefanus Sapta Dwi Lianto - 20240801196</a>
            </div>

            <div class="footer-links">
                <h4>Web Development</h4>
                <a>Dosen Pengampu: DEWI SETIOWATI , A.Md., S.Pd., M.Tr.Kom.</a>
                <a>Kelas         : KH001</a>
                <a>Kelas         : KH001</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p> Tahun Akademik 2025/2026</p>
        </div>
    </footer>

</body>

</html>