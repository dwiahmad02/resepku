<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResepKu — Kelola Kategori</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .row-edit-form { display: none; }
        .row-edit-form.show { display: table-row; }
        tr.row-view.hide { display: none; }
    </style>
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">Resepku <span style="font-size:13px;font-weight:normal;color:#888;">| Admin</span></a>
        <div class="nav-links">
            <a href="{{ route('home') }}"            class="nav-link">Home</a>
            <a href="{{ route('resep.dashboard') }}" class="nav-link">Dashboard Saya</a>
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Panel</a>
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

    @if (session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error">❌ {{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error) <p>❌ {{ $error }}</p> @endforeach
        </div>
    @endif

    <div class="judul-section">
        <h2>Kelola Kategori</h2>
        <hr>
    </div>

    <div class="admin-subnav">
        <a href="{{ route('admin.dashboard') }}">📊 Semua Resep</a>
        <a href="{{ route('admin.kategori.index') }}" class="active">🏷️ Kelola Kategori</a>
        <a href="{{ route('admin.users.index') }}">👥 Kelola User</a>
    </div>

    {{-- ===== FORM TAMBAH KATEGORI ===== --}}
    <div class="wrapper-tambah-kategori">
        <div class="card-tambah">
            <label>Tambah Kategori Baru</label>
            <form action="{{ route('admin.kategori.store') }}" method="POST" class="inline-form">
                @csrf
                <input type="text" name="nama_kategori" placeholder="Contoh: Makanan Berat" required style="flex:1; min-width:180px;">
                <button type="submit" class="btn-kecil">+ Tambah</button>
            </form>
        </div>
    </div>

    {{-- ===== TABEL KATEGORI ===== --}}
    <div class="wrapper-tabel">
        <table class="tabel-resep">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Jumlah Resep</th>
                    <th style="width:200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategori as $no => $kat)
                    <tr class="row-view" id="view-{{ $kat->id_kategori }}">
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $kat->nama_kategori }}</td>
                        <td>{{ $kat->jumlah_resep }}</td>
                        <td>
                            <button type="button" class="btn-edit" style="border:2px solid #17a2b8; background:white; cursor:pointer;"
                                    onclick="toggleEdit({{ $kat->id_kategori }})">Edit</button>
                            <a href="{{ route('admin.kategori.hapus', $kat->id_kategori) }}"
                               class="btn-hapus"
                               onclick="return confirm('Yakin ingin menghapus kategori {{ $kat->nama_kategori }}?')">Hapus</a>
                        </td>
                    </tr>
                    <tr class="row-edit-form" id="edit-{{ $kat->id_kategori }}">
                        <td colspan="4">
                            <form action="{{ route('admin.kategori.update', $kat->id_kategori) }}" method="POST" class="inline-form">
                                @csrf
                                <input type="text" name="nama_kategori" value="{{ $kat->nama_kategori }}" required style="flex:1;">
                                <button type="submit" class="btn-kecil">💾 Simpan</button>
                                <button type="button" class="btn-kecil" style="background:#999;" onclick="toggleEdit({{ $kat->id_kategori }})">Batal</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:30px;">🏷️ Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function toggleEdit(id) {
            document.getElementById('view-' + id).classList.toggle('hide');
            document.getElementById('edit-' + id).classList.toggle('show');
        }
    </script>

</body>
</html>
