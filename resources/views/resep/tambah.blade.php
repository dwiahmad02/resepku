<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep Baru — ResepKu</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .container-form { padding: 40px 30px; max-width: 640px; margin: 0 auto; }
        .form-group { margin-bottom: 22px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid #ccc;
            border-radius: 8px; font-size: 16px; box-sizing: border-box;
            font-family: inherit;
        }
        .btn-simpan {
            background-color: orange; color: white; border: none;
            padding: 13px 25px; border-radius: 8px; font-weight: bold;
            cursor: pointer; width: 100%; font-size: 16px; transition: 0.3s;
        }
        .btn-simpan:hover { background-color: #e69500; }
        .invalid-feedback { color: red; font-size: 13px; margin-top: 4px; }
        /* Dark mode inputs */
        body.dark-mode .form-group label  { color: white; }
        body.dark-mode .form-group input,
        body.dark-mode .form-group select,
        body.dark-mode .form-group textarea {
            background-color: #2a2a2a; color: white; border: 1px solid #444;
        }
        body.dark-mode h2 { color: white; }
    </style>
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">Resepku</a>
        <div class="nav-links">
            <a href="{{ route('home') }}"            class="nav-link">Home</a>
            <a href="{{ route('resep.dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ url('/login') }}"            class="nav-link">Login</a>
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

    <div class="container-form">
        <h2>Tambah Resep Baru</h2>
        <hr style="border: 2px solid orange; width: 50px; margin: 10px 0 30px 0;">

        {{-- Tampilkan error validasi --}}
        @if ($errors->any())
            <div class="alert-error" style="margin-bottom:20px;">
                @foreach ($errors->all() as $error)
                    <p>❌ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('resep.simpan') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Masakan <span style="color:red;">*</span></label>
                <input type="text" name="nama_masakan"
                       placeholder="Masukkan nama masakan"
                       value="{{ old('nama_masakan') }}" required>
            </div>

            <div class="form-group">
                <label>Kategori <span style="color:red;">*</span></label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id_kategori }}"
                            {{ old('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3"
                    placeholder="Ceritakan sedikit tentang masakan ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="form-group">
                <label>Bahan-Bahan</label>
                <textarea name="bahan" rows="5"
                    placeholder="Contoh:&#10;- 250gr Daging&#10;- 2 siung Bawang">{{ old('bahan') }}</textarea>
            </div>

            <div class="form-group">
                <label>Langkah Memasak</label>
                <textarea name="langkah_masak" rows="5"
                    placeholder="1. Potong daging...&#10;2. Tumis bumbu...">{{ old('langkah_masak') }}</textarea>
            </div>

            <div class="form-group">
                <label>Foto Masakan <span style="color:red;">*</span></label>
                <input type="file" name="foto" accept="image/*" required>
                <small style="color:#888; margin-top:5px; display:block;">Maks. 5MB (jpg, png, webp)</small>
            </div>

            <button type="submit" class="btn-simpan">💾 Simpan Resep</button>
        </form>
    </div>

</body>
</html>
