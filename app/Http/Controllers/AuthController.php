<?php

namespace App\Http\Controllers;

use App\Models\TbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ===================== LOGIN =====================
    public function showLogin()
    {
        if (Auth::guard('tbuser')->check()) return redirect('/');
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('tbuser')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }

    // ===================== GOOGLE LOGIN =====================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal, coba lagi.']);
        }

        // Cek apakah email sudah terdaftar
        $user = TbUser::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Buat akun baru otomatis dari data Google
            $user = TbUser::create([
                'username' => $googleUser->getName(),
                'email'    => $googleUser->getEmail(),
                'password' => Hash::make(str()->random(24)), // password acak, user login via Google
            ]);

            // Buat profil otomatis
            DB::table('tb_profile')->updateOrInsert(
                ['id_user' => $user->id],
                [
                    'nama_lengkap' => $googleUser->getName(),
                    'profile_pict' => null,
                ]
            );
        }

        // Login user
        Auth::guard('tbuser')->login($user);
        request()->session()->regenerate();

        return redirect('/');
    }

    // ===================== REGISTER =====================
    public function showRegister()
    {
        if (Auth::guard('tbuser')->check()) return redirect('/');
        return view('register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username'         => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'unique:tb_user,email'],
            'password'         => ['required', 'min:6'],
            'confirm-password' => ['required', 'same:password'],
        ], [
            'email.unique'          => 'Email sudah digunakan!',
            'confirm-password.same' => 'Password tidak cocok!',
        ]);

        $user = TbUser::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        DB::table('tb_profile')->updateOrInsert(
            ['id_user' => $user->id],
            ['nama_lengkap' => $validated['username'], 'profile_pict' => null]
        );

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Silakan login.');
    }

    // ===================== FORGOT PASSWORD (OTP) =====================
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:tb_user,email'],
        ], [
            'email.exists' => 'Email tidak terdaftar!',
        ]);

        // Buat OTP 6 digit
        $otp = rand(100000, 999999);

        // Simpan OTP ke database (expired 10 menit)
        DB::table('password_otp')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp'        => $otp,
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
            ]
        );

        // Kirim OTP via email
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Kode OTP Reset Password - Resepku');
        });

        return redirect()->route('otp.verify.form', ['email' => $request->email])
                         ->with('status', 'Kode OTP sudah dikirim ke email kamu. Berlaku 10 menit.');
    }

    public function showVerifyOtp(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'digits:6'],
        ]);

        $record = DB::table('password_otp')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP salah!']);
        }

        if (now()->isAfter($record->expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Minta kode baru.']);
        }

        // OTP valid — arahkan ke halaman reset password
        return redirect()->route('password.reset.form', [
            'email' => $request->email,
            'token' => base64_encode($request->email . '|' . $request->otp),
        ]);
    }

    public function showResetPassword(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'            => ['required', 'email'],
            'token'            => ['required'],
            'password'         => ['required', 'min:6'],
            'confirm-password' => ['required', 'same:password'],
        ], [
            'confirm-password.same' => 'Konfirmasi password tidak cocok!',
        ]);

        // Validasi token
        $decoded = base64_decode($request->token);
        [$tokenEmail, $tokenOtp] = explode('|', $decoded);

        $record = DB::table('password_otp')
            ->where('email', $request->email)
            ->where('otp', $tokenOtp)
            ->first();

        if (!$record || $tokenEmail !== $request->email) {
            return back()->withErrors(['password' => 'Sesi reset password tidak valid.']);
        }

        // Update password
        TbUser::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus OTP setelah digunakan
        DB::table('password_otp')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password berhasil direset! Silakan login.');
    }

    // ===================== PROFILE =====================
    public function profile()
    {
        $user   = Auth::guard('tbuser')->user();
        $profil = DB::table('tb_profile')->where('id_user', $user->id)->first();

        $resep_tersimpan = collect();
        if ($profil && !empty($profil->resep_tersimpan)) {
            $ids = array_filter(explode(',', $profil->resep_tersimpan));
            if (!empty($ids)) {
                $resep_tersimpan = DB::table('resep')
                    ->whereIn('id_resep', $ids)
                    ->select('id_resep', 'nama_masakan', 'foto', 'rating', 'nama_chef')
                    ->get();
            }
        }

        return view('auth.profile', compact('user', 'profil', 'resep_tersimpan'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('tbuser')->user();

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'foto'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = ['nama_lengkap' => $request->nama_lengkap];

        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $filename = 'profil_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profil'), $filename);

            $profil_lama = DB::table('tb_profile')->where('id_user', $user->id)->first();
            if ($profil_lama && !empty($profil_lama->profile_pict)) {
                $path_lama = public_path('uploads/profil/' . $profil_lama->profile_pict);
                if (file_exists($path_lama)) unlink($path_lama);
            }

            $data['profile_pict'] = $filename;
        }

        DB::table('tb_profile')->updateOrInsert(['id_user' => $user->id], $data);

        return back()->with('status', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::guard('tbuser')->user();

        $request->validate([
            'password_lama'   => ['required'],
            'password_baru'   => ['required', 'min:6'],
            'konfirmasi_baru' => ['required', 'same:password_baru'],
        ], [
            'konfirmasi_baru.same' => 'Konfirmasi password tidak cocok!',
            'password_baru.min'    => 'Password baru minimal 6 karakter!',
        ]);

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai!']);
        }

        TbUser::where('id', $user->id)->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('status_password', 'Password berhasil diubah!');
    }

    // ===================== LOGOUT =====================
    public function logout(Request $request)
    {
        Auth::guard('tbuser')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
