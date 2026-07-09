<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $kata_kunci         = $request->input('cari', '');
        $id_filter_kategori = $request->input('kategori', '');
        $sort                = $request->input('sort', 'terbaru');

        $query = DB::table('resep as r')
            ->leftJoin('kategori as k', 'r.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('tb_user as u', 'r.user_id', '=', 'u.id')
            ->select('r.*', 'k.nama_kategori', DB::raw('COALESCE(u.username, r.nama_chef, "Guest") as nama_chef'));

        if (!empty($kata_kunci)) {
            $query->where('r.nama_makanan', 'like', '%' . $kata_kunci . '%');
        }

        if (!empty($id_filter_kategori)) {
            $query->where('r.id_kategori', $id_filter_kategori);
        }

        switch ($sort) {
            case 'rating':
                $query->orderBy('r.rating', 'desc');
                break;
            case 'nama_asc':
                $query->orderBy('r.nama_makanan', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('r.nama_makanan', 'desc');
                break;
            default:
                $query->orderBy('r.id_resep', 'desc');
                break;
        }

        $data_resep = $query->get();

        return view('home.landingpage', compact(
            'data_resep',
            'kata_kunci',
            'id_filter_kategori',
            'sort'
        ));
    }
}
