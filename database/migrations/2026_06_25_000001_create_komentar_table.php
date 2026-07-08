<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel 'komentar' untuk menyimpan ulasan pengguna pada setiap resep.
     */
    public function up(): void
    {
        Schema::create('komentar', function (Blueprint $table) {
            $table->id('id_komentar');
            $table->unsignedBigInteger('id_resep');
            $table->string('username', 100);
            $table->text('isi_komentar');
            $table->timestamps();

            // Foreign key ke tabel resep (opsional, uncomment jika ingin)
            // $table->foreign('id_resep')->references('id_resep')->on('resep')->onDelete('cascade');
        });
    }

    /**
     * Rollback: hapus tabel 'komentar'.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};
