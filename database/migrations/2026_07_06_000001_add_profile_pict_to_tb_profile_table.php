<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom profile_pict ke tabel tb_profile.
     * Menyimpan nama file foto profil user (disimpan fisiknya di
     * public/uploads/profil/).
     */
    public function up(): void
    {
        Schema::table('tb_profile', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_profile', 'profile_pict')) {
                $table->string('profile_pict', 255)->nullable()->after('nama_lengkap');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_profile', function (Blueprint $table) {
            if (Schema::hasColumn('tb_profile', 'profile_pict')) {
                $table->dropColumn('profile_pict');
            }
        });
    }
};
