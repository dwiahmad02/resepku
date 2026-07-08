<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom role ke tabel tb_user.
     * Dipakai untuk membedakan akun 'admin' dan 'user' biasa.
     */
    public function up(): void
    {
        Schema::table('tb_user', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_user', 'role')) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_user', function (Blueprint $table) {
            if (Schema::hasColumn('tb_user', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
