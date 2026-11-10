# 🍳 ResepKu

Aplikasi web berbagi resep masakan. Pengguna bisa membuat akun, mengunggah resep lengkap dengan foto, memberi like, dan berkomentar. Dilengkapi panel admin untuk mengelola resep, kategori, dan pengguna.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?logo=sqlite&logoColor=white)
![Status](https://img.shields.io/badge/status-in%20development-yellow)

## ✨ Fitur

**Pengguna**
- Registrasi, login, dan logout
- Lupa password dengan verifikasi kode OTP lewat email
- Halaman profil: ubah data, foto profil, dan password
- Tambah, lihat, edit, dan hapus resep milik sendiri (CRUD)
- Upload foto resep
- Filter resep berdasarkan kategori
- Like dan komentar pada resep (AJAX, tanpa reload)
- Mode gelap 🌙

**Admin**
- Dashboard admin
- Kelola kategori resep
- Kelola pengguna dan ubah role
- Hapus resep yang melanggar

## 🛠️ Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, CSS, JavaScript |
| Database | SQLite (default), bisa diganti MySQL |
| Build tool | Vite |

## 🚀 Cara menjalankan

Pastikan sudah terpasang **PHP 8.2+**, **Composer**, dan **Node.js**.

```bash
# 1. Clone repository
git clone https://github.com/USERNAME/resepku.git
cd resepku

# 2. Install dependensi
composer install
npm install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate

# 4. Siapkan database (SQLite)
touch database/database.sqlite
php artisan migrate

# 5. Jalankan aplikasi
php artisan serve
npm run dev
```

Buka `http://localhost:8000` di browser.

### Pengaturan email (untuk OTP lupa password)
Secara default email hanya ditulis ke log (`MAIL_MAILER=log`), jadi kode OTP bisa dilihat di `storage/logs/laravel.log`. Untuk mengirim email sungguhan, isi konfigurasi `MAIL_*` di file `.env`.

## 📁 Struktur singkat

```
app/Http/Controllers/   → AuthController, ResepController, AdminController, HomeController
app/Http/Middleware/    → EnsureUserIsAdmin
resources/views/        → tampilan Blade (auth, resep, admin, home)
public/css/             → stylesheet tiap halaman
public/uploads/         → foto resep & foto profil
routes/web.php          → daftar route
database/migrations/    → skema database
```

## 📸 Tampilan

<!-- Ganti dengan screenshot aplikasimu, simpan di folder docs/ -->
<!-- ![Landing page](docs/landing.png) -->
<!-- ![Dashboard](docs/dashboard.png) -->

## 🗺️ Rencana pengembangan

- [ ] Fitur pencarian resep
- [ ] Pagination
- [ ] Simpan resep favorit
- [ ] Deploy online

## 👤 Pembuat

Dibuat oleh **Dwi Ahmad** sebagai proyek belajar Laravel.

- GitHub: [@USERNAME](https://github.com/USERNAME)
