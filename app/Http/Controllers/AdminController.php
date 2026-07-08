<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ============================================================
    // DASHBOARD ADMIN — Semua resep dari semua user
    // ============================================================
    public function dashboard(Request $request)
    {
        $filter = $request->input('kat', '');

        $query = DB::table('resep as r')
            ->leftJoin('kategori as k', 'r.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('tb_user as u', 'r.user_id', '=', 'u.id')
            ->select('r.*', 'k.nama_kategori', 'u.username as nama_pemilik');

        if (!empty($filter)) {
            $query->where('k.nama_kategori', $filter);
        }

        $data_resep   = $query->orderBy('r.id_resep', 'desc')->get();
        $filter_aktif = $filter;

        $stats = [
            'total_resep'    => DB::table('resep')->count(),
            'total_user'     => DB::table('tb_user')->count(),
            'total_kategori' => DB::table('kategori')->count(),
        ];

        return view('admin.dashboard', compact('data_resep', 'filter_aktif', 'stats'));
    }

    // ============================================================
    // HAPUS RESEP (siapa pun pemiliknya) — akses admin
    // ============================================================
    public function resepHapus($id)
    {
        $resep = DB::table('resep')->where('id_resep', $id)->first();

        if (!$resep) {
            return redirect()->route('admin.dashboard')->with('error', 'Resep tidak ditemukan.');
        }

        DB::table('resep')->where('id_resep', $id)->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Resep berhasil dihapus.');
    }

    // ============================================================
    // KELOLA KATEGORI
    // ============================================================
    public function kategoriIndex()
    {
        $kategori = DB::table('kategori as k')
            ->leftJoin('resep as r', 'r.id_kategori', '=', 'k.id_kategori')
            ->select('k.id_kategori', 'k.nama_kategori', DB::raw('COUNT(r.id_resep) as jumlah_resep'))
            ->groupBy('k.id_kategori', 'k.nama_kategori')
            ->orderBy('k.nama_kategori')
            ->get();

        return view('admin.kategori', compact('kategori'));
    }

    public function kategoriStore(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
        ], [
            'nama_kategori.unique' => 'Kategori dengan nama itu sudah ada.',
        ]);

        DB::table('kategori')->insert([
            'nama_kategori' => $request->input('nama_kategori'),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function kategoriUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $id . ',id_kategori',
        ], [
            'nama_kategori.unique' => 'Kategori dengan nama itu sudah ada.',
        ]);

        DB::table('kategori')->where('id_kategori', $id)->update([
            'nama_kategori' => $request->input('nama_kategori'),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function kategoriHapus($id)
    {
        $dipakai = DB::table('resep')->where('id_kategori', $id)->count();

        if ($dipakai > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', "Kategori tidak bisa dihapus karena masih dipakai di {$dipakai} resep.");
        }

        DB::table('kategori')->where('id_kategori', $id)->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }

    // ============================================================
    // KELOLA USER (tb_user)
    // ============================================================
    public function userIndex()
    {
        $users = DB::table('tb_user')
            ->select('id', 'username', 'email', 'role', 'created_at')
            ->orderBy('username')
            ->get();

        return view('admin.users', compact('users'));
    }

    public function userUpdateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        if ((int) $id === (int) Auth::guard('tbuser')->id() && $request->input('role') !== 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Kamu tidak bisa menurunkan role akun sendiri.');
        }

        DB::table('tb_user')->where('id', $id)->update([
            'role' => $request->input('role'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Role user berhasil diperbarui!');
    }

    public function userHapus($id)
    {
        if ((int) $id === (int) Auth::guard('tbuser')->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Kamu tidak bisa menghapus akun sendiri.');
        }

        DB::table('tb_user')->where('id', $id)->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
