<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResepKu — Dashboard Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">Resepku <span style="font-size:13px;font-weight:normal;color:#888;">| Admin</span></a>
        <div class="nav-links">
            <a href="{{ route('home') }}"            class="nav-link">Home</a>
            <a href="{{ route('resep.dashboard') }}" class="nav-link">Dashboard Saya</a>
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">Admin Panel</a>
            <a href="{{ route('profile') }}"         class="nav-link">Profil</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Logout</button>
            </form>
            <button class="btn-icon" id="darkMode" title="Ganti tema">🌙</button>
        </div>
    </nav>

    <script>
        const darkMode = document.getElementById('darkMode');
        const body = document.body;
        if (localStorage.getItem('theme') === 'dark') { body.classList.add('dark-mode'); darkMode.textContent = '☀️'; }
        darkMode.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            darkMode.textContent = isDark ? '☀️' : '🌙';
        });
    </script>

    {{-- ===== PESAN FLASH ===== --}}
    @if (session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error">❌ {{ session('error') }}</div>
    @endif

    <div class="judul-section">
        <h2>Dashboard Admin</h2>
        <hr>
    </div>

    {{-- ===== SUB NAVIGASI ADMIN ===== --}}
    <div class="admin-subnav">
        <a href="{{ route('admin.dashboard') }}" class="active">📊 Semua Resep</a>
        <a href="{{ route('admin.kategori.index') }}">🏷️ Kelola Kategori</a>
        <a href="{{ route('admin.users.index') }}">👥 Kelola User</a>
    </div>

    {{-- ===== KARTU STATISTIK ===== --}}
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-icon">🍽️</div>
            <div class="stat-number">{{ $stats['total_resep'] }}</div>
            <div class="stat-label">Total Resep</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number">{{ $stats['total_user'] }}</div>
            <div class="stat-label">Total User</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏷️</div>
            <div class="stat-number">{{ $stats['total_kategori'] }}</div>
            <div class="stat-label">Total Kategori</div>
        </div>
    </div>

    {{-- ===== FILTER KATEGORI ===== --}}
    <div class="filter-group">
        <a href="{{ route('admin.dashboard') }}"
           class="btn-filter {{ $filter_aktif == '' ? 'active' : '' }}">Semua</a>
        <a href="{{ route('admin.dashboard', ['kat' => 'Makanan Berat']) }}"
           class="btn-filter {{ $filter_aktif == 'Makanan Berat' ? 'active' : '' }}">Makanan Berat</a>
        <a href="{{ route('admin.dashboard', ['kat' => 'Makanan Ringan']) }}"
           class="btn-filter {{ $filter_aktif == 'Makanan Ringan' ? 'active' : '' }}">Makanan Ringan</a>
        <a href="{{ route('admin.dashboard', ['kat' => 'Dessert']) }}"
           class="btn-filter {{ $filter_aktif == 'Dessert' ? 'active' : '' }}">Dessert</a>
        <a href="{{ route('admin.dashboard', ['kat' => 'Minuman']) }}"
           class="btn-filter {{ $filter_aktif == 'Minuman' ? 'active' : '' }}">Minuman</a>
    </div>

    {{-- ===== TABEL RESEP (SEMUA USER) ===== --}}
    <div class="wrapper-tabel">
        <table class="tabel-resep">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Masakan</th>
                    <th>Kategori</th>
                    <th>Pemilik</th>
                    <th style="width:220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data_resep as $no => $row)
                    @php
                        $foto = !empty($row->foto) ? $row->foto : ($row->gambar ?? '');
                        $nama = !empty($row->nama_masakan) ? $row->nama_masakan : $row->nama_makanan;
                    @endphp
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>
                            <img src="{{ asset('uploads/' . $foto) }}"
                                 class="foto-tabel"
                                 onerror="this.onerror=null; this.src='{{ asset('img/default.jpg') }}';">
                        </td>
                        <td>{{ $nama }}</td>
                        <td><span class="badge-kategori">{{ $row->nama_kategori ?? 'Tanpa Kategori' }}</span></td>
                        <td>{{ $row->nama_pemilik ?? '—' }}</td>
                        <td>
                            <a href="{{ route('resep.detail', $row->id_resep) }}" class="btn-detail">Detail</a>
                            <a href="{{ route('resep.edit',   $row->id_resep) }}" class="btn-edit">Edit</a>
                            <a href="{{ route('admin.resep.hapus', $row->id_resep) }}"
                               class="btn-hapus"
                               onclick="return confirm('Yakin ingin menghapus resep ini? Resep milik user lain juga bisa terhapus.')">Hapus</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 30px;">
                            🍽️ Belum ada resep tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
