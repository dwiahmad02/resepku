<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResepController extends Controller
{
    // ============================================================
    // DASHBOARD — Tampilkan semua resep dalam format tabel
    // ============================================================
    public function dashboard(Request $request)
    {
        $filter = $request->input('kat', '');

        $query = DB::table('resep as r')
            ->leftJoin('kategori as k', 'r.id_kategori', '=', 'k.id_kategori')
            ->select('r.*', 'k.nama_kategori')
            ->where('r.user_id', Auth::guard('tbuser')->id());

        if (!empty($filter)) {
            $query->where('k.nama_kategori', 'LIKE', "%{$filter}%");
        }

        $data_resep = $query->orderBy('r.id_resep', 'desc')->get();
        $filter_aktif = $filter;

        return view('resep.dashboard', compact('data_resep', 'filter_aktif'));
    }

    // ============================================================
    // TAMBAH — Form tambah resep baru
    // ============================================================
    public function tambah()
    {
        $kategori = DB::table('kategori')->get();
        return view('resep.tambah', compact('kategori'));
    }

    // ============================================================
    // SIMPAN — Proses penyimpanan resep baru ke database
    // ============================================================
    public function simpan(Request $request)
    {
        $request->validate([
            'nama_masakan'  => 'required|string|max:255',
            'kategori'      => 'required|integer',
            'foto'          => 'required|image|max:5120',
        ]);

        $nama_foto = null;
        if ($request->hasFile('foto')) {
            $file      = $request->file('foto');
            $nama_foto = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $nama_foto);
        }

        DB::table('resep')->insert([
            'nama_makanan'  => $request->input('nama_masakan'),
            'id_kategori'   => $request->input('kategori'),
            'gambar'        => $nama_foto,
            'deskripsi'     => $request->input('deskripsi', ''),
            'bahan'         => $request->input('bahan', ''),
            'langkah_masak' => $request->input('langkah_masak', ''),
            'user_id'       => Auth::guard('tbuser')->id(),
        ]);

        return redirect()->route('resep.dashboard')
            ->with('success', 'Resep berhasil ditambahkan!');
    }

    // ============================================================
    // DETAIL — Halaman detail satu resep beserta komentar & like
    // ============================================================
    public function detail($id)
    {
        $resep = DB::table('resep as r')
            ->leftJoin('kategori as k', 'r.id_kategori', '=', 'k.id_kategori')
            ->select('r.*', 'k.nama_kategori')
            ->where('r.id_resep', $id)
            ->first();

        if (!$resep) {
            return redirect()->route('resep.dashboard')
                ->with('error', 'Resep tidak ditemukan.');
        }

        $komentar = DB::table('komentar as km')
            ->leftJoin('tb_user as u', 'km.id_user', '=', 'u.id')
            ->select('km.*', 'u.username')
            ->where('km.id_resep', $id)
            ->orderBy('km.id_komentar', 'desc')
            ->get();

        // cek apakah user yang sedang login sudah like resep ini
        $sudah_like = false;
        if (Auth::guard('tbuser')->check()) {
            $sudah_like = DB::table('resep_likes')
                ->where('id_resep', $id)
                ->where('id_user', Auth::guard('tbuser')->id())
                ->exists();
        }

        return view('resep.detail', compact('resep', 'komentar', 'sudah_like'));
    }

    // ============================================================
    // EDIT — Form edit resep yang sudah ada
    // ============================================================
    public function edit($id)
    {
        $resep    = DB::table('resep')->where('id_resep', $id)->first();
        $kategori = DB::table('kategori')->get();

        if (!$resep) {
            return redirect()->route('resep.dashboard')
                ->with('error', 'Resep tidak ditemukan.');
        }

        $user = Auth::guard('tbuser')->user();
        if ($resep->user_id && $resep->user_id != $user->id && $user->role !== 'admin') {
            abort(403, 'Kamu tidak punya akses ke resep ini.');
        }

        return view('resep.edit', compact('resep', 'kategori'));
    }

    // ============================================================
    // UPDATE — Proses update resep yang sudah ada
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_masakan'  => 'required|string|max:255',
            'kategori'      => 'required|integer',
            'foto'          => 'nullable|image|max:5120',
        ]);

        $resep_lama = DB::table('resep')->where('id_resep', $id)->first();

        if (!$resep_lama) {
            return redirect()->route('resep.dashboard')->with('error', 'Resep tidak ditemukan.');
        }

        $user = Auth::guard('tbuser')->user();
        if ($resep_lama->user_id && $resep_lama->user_id != $user->id && $user->role !== 'admin') {
            abort(403, 'Kamu tidak punya akses ke resep ini.');
        }

        $data = [
            'nama_makanan'  => $request->input('nama_masakan'),
            'id_kategori'   => $request->input('kategori'),
            'bahan'         => $request->input('bahan', ''),
            'langkah_masak' => $request->input('langkah_masak', ''),
            'deskripsi'     => $request->input('deskripsi', ''),
        ];

        if ($request->hasFile('foto')) {
            $file           = $request->file('foto');
            $nama_foto      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $nama_foto);
            $data['gambar'] = $nama_foto;
        }

        DB::table('resep')->where('id_resep', $id)->update($data);

        return redirect()->route('resep.dashboard')
            ->with('success', 'Resep berhasil diperbarui!');
    }

    // ============================================================
    // HAPUS — Hapus resep dari database
    // ============================================================
    public function hapus($id)
    {
        $resep = DB::table('resep')->where('id_resep', $id)->first();

        if (!$resep) {
            return redirect()->route('resep.dashboard')->with('error', 'Resep tidak ditemukan.');
        }

        $user = Auth::guard('tbuser')->user();
        if ($resep->user_id && $resep->user_id != $user->id && $user->role !== 'admin') {
            abort(403, 'Kamu tidak punya akses ke resep ini.');
        }

        DB::table('resep')->where('id_resep', $id)->delete();

        return redirect()->route('resep.dashboard')
            ->with('success', 'Resep berhasil dihapus!');
    }

    // ============================================================
    // SIMPAN KOMENTAR — wajib login
    // ============================================================
    public function simpanKomentar(Request $request)
    {
        if (!Auth::guard('tbuser')->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login untuk memberi komentar.');
        }

        $request->validate([
            'id_resep'     => 'required|integer',
            'isi_komentar' => 'required|string',
        ]);

        DB::table('komentar')->insert([
            'id_resep'     => $request->input('id_resep'),
            'id_user'      => Auth::guard('tbuser')->id(),
            'isi_komentar' => $request->input('isi_komentar'),
            'tanggal'      => now(),
        ]);

        return redirect()->route('resep.detail', $request->input('id_resep'))
            ->with('success', 'Ulasan berhasil dikirim!');
    }

    // ============================================================
    // TOGGLE LIKE — wajib login, per-user (bukan cuma counter)
    // ============================================================
    public function toggleLike(Request $request)
    {
        if (!Auth::guard('tbuser')->check()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Silakan login dulu untuk menyukai resep.',
            ], 401);
        }

        $id_resep = $request->input('id_resep');
        $id_user  = Auth::guard('tbuser')->id();

        $sudahLike = DB::table('resep_likes')
            ->where('id_resep', $id_resep)
            ->where('id_user', $id_user)
            ->exists();

        if ($sudahLike) {
            DB::table('resep_likes')
                ->where('id_resep', $id_resep)
                ->where('id_user', $id_user)
                ->delete();
            DB::table('resep')->where('id_resep', $id_resep)->decrement('suka');
            $liked = false;
        } else {
            DB::table('resep_likes')->insert([
                'id_resep'   => $id_resep,
                'id_user'    => $id_user,
                'created_at' => now(),
            ]);
            DB::table('resep')->where('id_resep', $id_resep)->increment('suka');
            $liked = true;
        }

        $new_count = DB::table('resep')->where('id_resep', $id_resep)->value('suka');

        return response()->json([
            'status'    => 'success',
            'liked'     => $liked,
            'new_count' => $new_count,
        ]);
    }
}
