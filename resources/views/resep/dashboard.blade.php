<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResepKu — Dashboard Koleksi</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
        <h2>Koleksi Resep Saya</h2>
        <hr>
    </div>

    {{-- ===== FILTER KATEGORI ===== --}}
    <div class="filter-group">
        <a href="{{ route('resep.dashboard') }}"
           class="btn-filter {{ $filter_aktif == '' ? 'active' : '' }}">Semua</a>
        <a href="{{ route('resep.dashboard', ['kat' => 'Makanan']) }}"
           class="btn-filter {{ $filter_aktif == 'Makanan' ? 'active' : '' }}">Makanan Berat</a>
        <a href="{{ route('resep.dashboard', ['kat' => 'Minuman']) }}"
           class="btn-filter {{ $filter_aktif == 'Minuman' ? 'active' : '' }}">Makanan Ringan</a>
        <a href="{{ route('resep.dashboard', ['kat' => 'Cemilan']) }}"
           class="btn-filter {{ $filter_aktif == 'Cemilan' ? 'active' : '' }}">Dessert</a>
        <a href="{{ route('resep.dashboard', ['kat' => 'Dessert']) }}"
           class="btn-filter {{ $filter_aktif == 'Dessert' ? 'active' : '' }}">Minuman</a>
    </div>

    {{-- ===== TABEL RESEP ===== --}}
    <div class="wrapper-tabel">
        <table class="tabel-resep">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Masakan</th>
                    <th>Kategori</th>
                    <th style="width:260px;">Aksi</th>
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
                        <td>
                            <a href="{{ route('resep.detail', $row->id_resep) }}" class="btn-detail">Detail</a>
                            <a href="{{ route('resep.edit',   $row->id_resep) }}" class="btn-edit">Edit</a>
                            <a href="{{ route('resep.hapus',  $row->id_resep) }}"
                               class="btn-hapus"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus resep ini?')">Hapus</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 30px;">
                            🍽️ Belum ada resep tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== TOMBOL TAMBAH ===== --}}
    <div class="wrapper-tambah">
        <a href="{{ route('resep.tambah') }}" class="btn-tambah">+ Tambah Resep Baru</a>
    </div>

</body>
</html>