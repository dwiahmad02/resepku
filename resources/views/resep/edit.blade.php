<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resep — ResepKu</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .form-edit {
            padding: 30px; max-width: 700px; margin: 30px auto;
            background: #fffaf0; border-radius: 20px;
            border: 2px solid orange; box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #444; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%; padding: 12px; border-radius: 10px;
            border: 1px solid #ccc; font-family: inherit; box-sizing: border-box;
        }
        .btn-update {
            background-color: orange; color: white; border: none; padding: 15px;
            border-radius: 10px; width: 100%; font-weight: bold; cursor: pointer;
            transition: 0.3s; font-size: 16px;
        }
        .btn-update:hover { background-color: #e69500; transform: translateY(-2px); }
        /* Dark mode */
        body.dark-mode .form-edit { background: #1e1e1e; border-color: #555; }
        body.dark-mode .form-group label { color: #ddd; }
        body.dark-mode .form-group input,
        body.dark-mode .form-group select,
        body.dark-mode .form-group textarea {
            background: #2a2a2a; color: white; border-color: #444;
        }
    </style>
</head>
<body>

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

    @if ($errors->any())
        <div class="alert-error" style="margin: 10px 30px;">
            @foreach ($errors->all() as $error) <p>❌ {{ $error }}</p> @endforeach
        </div>
    @endif

    <div class="form-edit">
        <h2 style="text-align:center; margin-bottom:25px;">✏️ Update Data Resep</h2>

        @php
            $foto_lama = !empty($resep->foto) ? $resep->foto : ($resep->gambar ?? '');
            $nama_lama = !empty($resep->nama_masakan) ? $resep->nama_masakan : ($resep->nama_makanan ?? '');
        @endphp

        <form action="{{ route('resep.update', $resep->id_resep) }}"
              method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Masakan</label>
                <input type="text" name="nama_masakan"
                       value="{{ old('nama_masakan', $nama_lama) }}" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori">
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id_kategori }}"
                            {{ $resep->id_kategori == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3">{{ old('deskripsi', $resep->deskripsi ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Bahan-Bahan</label>
                <textarea name="bahan" rows="4">{{ old('bahan', $resep->bahan ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Langkah Memasak</label>
                <textarea name="langkah_masak" rows="6">{{ old('langkah_masak', $resep->langkah_masak ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Foto Masakan</label>
                @if ($foto_lama)
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('uploads/' . $foto_lama) }}"
                             width="120"
                             style="border-radius:10px; border:1px solid #ddd;"
                             onerror="this.style.display='none'">
                        <p style="font-size:12px; color:#888; margin-top:5px;">Foto saat ini: {{ $foto_lama }}</p>
                    </div>
                @endif
                <input type="file" name="foto" accept="image/*">
                <small style="color:#666;">*Kosongkan jika tidak ingin mengganti foto</small>
            </div>

            <button type="submit" class="btn-update">SIMPAN PERUBAHAN DATA</button>
        </form>
    </div>

</body>
</html>
