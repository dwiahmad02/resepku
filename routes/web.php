<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// ============================================================
// LANDING PAGE
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================================================
// AUTH — Guest only (login & register)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ============================================================
// AUTH — Harus login (profil, ganti password, logout)
// ============================================================
Route::middleware('auth:tbuser')->group(function () {
    Route::get('/profile',            [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update',    [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password',  [AuthController::class, 'updatePassword'])->name('profile.password');
    Route::post('/logout',            [AuthController::class, 'logout'])->name('logout');
});

// ============================================================
// DASHBOARD RESEP (CRUD) — butuh login, kelola resep sendiri
// ============================================================
Route::middleware('auth:tbuser')->group(function () {
    Route::get('/dashboard',            [ResepController::class, 'dashboard'])->name('resep.dashboard');
    Route::get('/tambah-resep',         [ResepController::class, 'tambah'])->name('resep.tambah');
    Route::post('/simpan-resep',        [ResepController::class, 'simpan'])->name('resep.simpan');
    Route::get('/resep/{id}',           [ResepController::class, 'detail'])->name('resep.detail');
    Route::get('/resep/{id}/edit',      [ResepController::class, 'edit'])->name('resep.edit');
    Route::post('/resep/{id}/update',   [ResepController::class, 'update'])->name('resep.update');
    Route::get('/resep/{id}/hapus',     [ResepController::class, 'hapus'])->name('resep.hapus');

    // AJAX Endpoints
    Route::post('/simpan-komentar',     [ResepController::class, 'simpanKomentar'])->name('resep.komentar');
    Route::post('/toggle-like',         [ResepController::class, 'toggleLike'])->name('resep.like');
});

// ============================================================
// DASHBOARD ADMIN — butuh login + role admin
// ============================================================
Route::middleware(['auth:tbuser', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',             [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/resep/{id}/hapus',      [AdminController::class, 'resepHapus'])->name('resep.hapus');

    Route::get('/kategori',              [AdminController::class, 'kategoriIndex'])->name('kategori.index');
    Route::post('/kategori',             [AdminController::class, 'kategoriStore'])->name('kategori.store');
    Route::post('/kategori/{id}/update', [AdminController::class, 'kategoriUpdate'])->name('kategori.update');
    Route::get('/kategori/{id}/hapus',   [AdminController::class, 'kategoriHapus'])->name('kategori.hapus');

    Route::get('/users',                 [AdminController::class, 'userIndex'])->name('users.index');
    Route::post('/users/{id}/role',      [AdminController::class, 'userUpdateRole'])->name('users.role');
    Route::get('/users/{id}/hapus',      [AdminController::class, 'userHapus'])->name('users.hapus');
});
