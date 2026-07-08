<?php

namespace Database\Seeders;

use App\Models\Resep;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResepSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user demo jika belum ada
        $user = User::firstOrCreate(
            ['email' => 'demo@resepku.id'],
            ['name' => 'Aku Kaya', 'password' => bcrypt('password')]
        );

        Resep::create([
            'user_id'      => $user->id,
            'judul'        => 'Bakso Malang',
            'kategori'     => 'Makanan Berat',
            'deskripsi'    => 'Bakso khas Malang yang lengkap dengan tahu, siomay, mi, dan kuah kaldu sapi yang gurih segar. Sajian ini sangat cocok untuk makan siang bersama keluarga.',
            'bahan'        => [
                'Bakso sapi 10 butir',
                'Tahu goreng 5 potong',
                'Siomay 5 buah',
                'Mi kuning 200g (rebus)',
                'Bihun 100g (seduh)',
                'Kol 100g (iris)',
                'Daun bawang 2 batang (iris)',
                'Bawang goreng secukupnya',
                // Kuah Kaldu
                'Tulang sapi 500g',
                'Air 2 liter',
                'Bawang putih 5 siung',
                'Bawang merah 3 siung',
                'Jahe 1 ruas',
                'Garam & lada secukupnya',
            ],
            'langkah'      => [
                'Rebus tulang sapi dengan air hingga mendidih, buang buih yang muncul.',
                'Masukkan bawang putih, bawang merah, dan jahe yang sudah digeprek. Rebus api kecil 1 jam.',
                'Saring kaldu, tambahkan garam dan lada secukupnya.',
                'Masukkan bakso ke kaldu mendidih hingga mengapung.',
                'Siapkan mangkuk, isi dengan mi, bihun, kol, tahu, siomay, dan bakso.',
                'Siram kuah panas, taburi bawang goreng dan daun bawang.',
                'Sajikan dengan sambal dan kecap manis.',
            ],
            'tips'         => 'Untuk kaldu lebih gurih, tambahkan sedikit kaldu blok atau rebus tulang lebih lama. Bakso akan lebih enak jika direndam dulu dalam kuah hangat sebelum disajikan.',
            'durasi_menit' => 90,
            'porsi'        => 4,
            'kesulitan'    => 'Sedang',
            'rating'       => 9.2,
            'likes'        => 16,
        ]);
    }
}
