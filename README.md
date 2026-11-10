# ResepKu

Website berbagi resep masakan, dibuat untuk tugas kuliah. Pengguna bisa daftar, upload resep beserta fotonya, lalu resep itu bisa di-like dan dikomentari pengguna lain. Ada juga halaman admin untuk mengelola resep, kategori, dan user.

Dibuat pakai Laravel 12 (PHP) dengan tampilan Blade, CSS, dan JavaScript.

## Fitur

Untuk user:
- Daftar, login, logout
- Lupa password lewat kode OTP yang dikirim ke email
- Edit profil, foto profil, dan ganti password
- Tambah, lihat, edit, dan hapus resep sendiri
- Upload foto resep
- Filter resep berdasarkan kategori
- Like dan komentar resep
- Mode gelap

Untuk admin:
- Dashboard admin
- Kelola kategori
- Kelola user dan role
- Hapus resep

## Cara menjalankan

Butuh PHP 8.2 ke atas, Composer, dan Node.js.

```bash
git clone https://github.com/USERNAME/resepku.git
cd resepku

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate

php artisan serve
npm run dev
```

Lalu buka `http://localhost:8000`.

Soal email: secara default `MAIL_MAILER=log`, jadi email tidak benar-benar terkirim. Kode OTP bisa dilihat di `storage/logs/laravel.log`. Kalau mau kirim email sungguhan, isi pengaturan `MAIL_*` di file `.env`.

## Struktur folder

- `app/Http/Controllers/` : logika aplikasi (Auth, Resep, Admin, Home)
- `resources/views/` : tampilan Blade
- `public/css/` : file CSS
- `public/uploads/` : foto resep dan foto profil
- `routes/web.php` : daftar route
- `database/migrations/` : struktur database

## Pembuat

Dwi Ahmad Maulana, Lucas Gabriel Mahatan, Stefanus Sapta Dwi Lianto
