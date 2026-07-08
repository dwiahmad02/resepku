<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResepKu — Kelola User</title>
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

    <div class="judul-section">
        <h2>Kelola User</h2>
        <hr>
    </div>

    <div class="admin-subnav">
        <a href="{{ route('admin.dashboard') }}">📊 Semua Resep</a>
        <a href="{{ route('admin.kategori.index') }}">🏷️ Kelola Kategori</a>
        <a href="{{ route('admin.users.index') }}" class="active">👥 Kelola User</a>
    </div>

    {{-- ===== TABEL USER ===== --}}
    <div class="wrapper-tabel">
        <table class="tabel-resep">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th style="width:260px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $no => $u)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $u->username }}</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge-role {{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                        <td>{{ \Illuminate\Support\Carbon::parse($u->created_at)->format('d M Y') }}</td>
                        <td>
                            @if ($u->id === Auth::guard('tbuser')->id())
                                <span style="font-size:12px; color:#999;">Ini akun kamu</span>
                            @else
                                <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="inline-form" style="display:inline-flex;">
                                    @csrf
                                    <select name="role">
                                        <option value="user"  {{ $u->role === 'user'  ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-kecil">Simpan</button>
                                </form>
                                <a href="{{ route('admin.users.hapus', $u->id) }}"
                                   class="btn-hapus"
                                   onclick="return confirm('Yakin ingin menghapus user {{ $u->username }}? Resep miliknya tidak ikut terhapus.')">Hapus</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px;">👥 Belum ada user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
