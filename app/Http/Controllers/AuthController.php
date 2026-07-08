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
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('tbuser')->attempt($credentials)) {
            $request->session()->regenerate();

            $user    = Auth::guard('tbuser')->user();
            $default = $user->role === 'admin' ? route('admin.dashboard') : '/';

            return redirect()->intended($default);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }

    // ===================== REGISTER =====================
    public function showRegister()
    {
        if (Auth::guard('tbuser')->check()) return redirect('/');
        return view('auth.register');
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

    // ===================== PROFILE =====================
    public function profile()
    {
        $user   = Auth::guard('tbuser')->user();
        $profil = DB::table('tb_profile')->where('id_user', $user->id)->first();

        // Resep tersimpan -- diambil langsung dari resep_likes, sumber data yang
        // sesungguhnya diisi oleh tombol like/bookmark di halaman detail resep.
        $resep_tersimpan = DB::table('resep_likes as rl')
            ->join('resep as r', 'rl.id_resep', '=', 'r.id_resep')
            ->select(
                'r.id_resep',
                'r.nama_makanan as nama_masakan',
                'r.gambar as foto',
                'r.rating',
                'r.nama_chef',
                'rl.created_at'
            )
            ->where('rl.id_user', $user->id)
            ->orderBy('rl.created_at', 'desc')
            ->get();

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
