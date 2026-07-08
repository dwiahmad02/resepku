<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom user_id ke tabel `resep` (tabel yang dipakai
     * dashboard CRUD resep pribadi, lewat DB::table('resep')).
     *
     * Kolom ini merujuk ke id di tabel tb_user (bukan tabel users
     * bawaan Laravel), karena sistem login project ini pakai guard
     * & tabel custom tb_user.
     */
    public function up(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            if (!Schema::hasColumn('resep', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id_resep');
                $table->index('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            if (Schema::hasColumn('resep', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
