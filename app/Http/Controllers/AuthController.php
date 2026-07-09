<?php

namespace App\Http\Controllers;

use App\Models\TbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

        DB::table('tb_profile')->insert([
            'id_user'      => $user->id,
            'nama_lengkap' => $validated['username'],
            'profile_pict' => null,
        ]);

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Silakan login.');
    }

    // ===================== LUPA PASSWORD (manual, tanpa email) =====================

    // Form untuk memasukkan email
    public function showForgotPassword()
    {
        if (Auth::guard('tbuser')->check()) return redirect('/');
        return view('forgot-password');
    }

    // Cek apakah email terdaftar, lalu simpan sesi sementara untuk lanjut ke form reset
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = TbUser::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar!',
            ])->onlyInput('email');
        }

        // Simpan id user di session sebagai penanda "boleh reset password"
        // (menggantikan token reset via email karena mode manual/lokal)
        $request->session()->put('reset_user_id', $user->id);

        return redirect()->route('password.reset');
    }

    // Form untuk memasukkan password baru
    public function showResetPassword(Request $request)
    {
        if (!$request->session()->has('reset_user_id')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Silakan masukkan email Anda terlebih dahulu.']);
        }

        return view('reset-password');
    }

    // Proses simpan password baru
    public function resetPassword(Request $request)
    {
        $userId = $request->session()->get('reset_user_id');

        if (!$userId) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi reset password sudah berakhir, silakan ulangi.']);
        }

        $request->validate([
            'password'          => ['required', 'min:6'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'password_confirmation.same' => 'Konfirmasi password tidak cocok!',
            'password.min'               => 'Password minimal 6 karakter!',
        ]);

        TbUser::where('id', $userId)->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus sesi reset supaya tidak bisa dipakai ulang
        $request->session()->forget('reset_user_id');

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login.');
    }

    // ===================== PROFILE =====================
    public function profile()
    {
        $user   = Auth::guard('tbuser')->user();
        $profil = DB::table('tb_profile')->where('id_user', $user->id)->first();

        // Resep tersimpan — ambil dari tabel resep berdasarkan id yang ada di tb_profile
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

    // ===================== EDIT PROFIL =====================
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('tbuser')->user();

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:500'],
            'foto'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'File harus berupa gambar!',
            'foto.max'   => 'Ukuran foto maksimal 2MB!',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'bio'          => $request->bio,
        ];

        // Proses upload foto kalau ada
        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $filename = 'profil_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profil'), $filename);

            // Hapus foto lama kalau ada
            $profil_lama = DB::table('tb_profile')->where('id_user', $user->id)->first();
            if ($profil_lama && !empty($profil_lama->profile_pict)) {
                $path_lama = public_path('uploads/profil/' . $profil_lama->profile_pict);
                if (file_exists($path_lama)) {
                    unlink($path_lama);
                }
            }

            $data['profile_pict'] = $filename;
        }

        DB::table('tb_profile')->updateOrInsert(
            ['id_user' => $user->id],
            $data
        );

        return back()->with('status', 'Profil berhasil diperbarui!');
    }

    // ===================== GANTI PASSWORD =====================
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('tbuser')->user();

        $request->validate([
            'password_lama'    => ['required'],
            'password_baru'    => ['required', 'min:6'],
            'konfirmasi_baru'  => ['required', 'same:password_baru'],
        ], [
            'konfirmasi_baru.same' => 'Konfirmasi password tidak cocok!',
            'password_baru.min'    => 'Password baru minimal 6 karakter!',
        ]);

        // Cek password lama
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
