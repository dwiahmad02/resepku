<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\AuthController;

// ============================================================
// LANDING PAGE
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================================================
// AUTH — Guest only
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.post');

    // Google OAuth
    Route::get('/auth/google',          [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

    // Forgot Password + OTP
    Route::get('/forgot-password',          [AuthController::class, 'showForgotPassword'])->name('forgot.password');
    Route::post('/forgot-password',         [AuthController::class, 'sendOtp'])->name('forgot.password.send');
    Route::get('/verify-otp',               [AuthController::class, 'showVerifyOtp'])->name('otp.verify.form');
    Route::post('/verify-otp',              [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::get('/reset-password',           [AuthController::class, 'showResetPassword'])->name('password.reset.form');
    Route::post('/reset-password',          [AuthController::class, 'resetPassword'])->name('password.reset');
});

// ============================================================
// AUTH — Harus login
// ============================================================
Route::middleware('auth:tbuser')->group(function () {
    Route::get('/profile',           [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update',   [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    Route::post('/logout',           [AuthController::class, 'logout'])->name('logout');
});

// ============================================================
// DASHBOARD RESEP (CRUD)
// ============================================================
Route::get('/dashboard',           [ResepController::class, 'dashboard'])->name('resep.dashboard');
Route::get('/tambah-resep',        [ResepController::class, 'tambah'])->name('resep.tambah');
Route::post('/simpan-resep',       [ResepController::class, 'simpan'])->name('resep.simpan');
Route::get('/resep/{id}',          [ResepController::class, 'detail'])->name('resep.detail');
Route::get('/resep/{id}/edit',     [ResepController::class, 'edit'])->name('resep.edit');
Route::post('/resep/{id}/update',  [ResepController::class, 'update'])->name('resep.update');
Route::get('/resep/{id}/hapus',    [ResepController::class, 'hapus'])->name('resep.hapus');

// AJAX Endpoints
Route::post('/simpan-komentar',    [ResepController::class, 'simpanKomentar'])->name('resep.komentar');
Route::post('/toggle-like',        [ResepController::class, 'toggleLike'])->name('resep.like');
