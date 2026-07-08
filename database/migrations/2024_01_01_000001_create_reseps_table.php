<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->enum('kategori', ['Makanan Berat', 'Makanan Ringan', 'Dessert', 'Minuman']);
            $table->text('deskripsi');
            $table->json('bahan');        // array of string
            $table->json('langkah');     // array of string
            $table->string('tips')->nullable();
            $table->string('foto')->nullable();
            $table->integer('durasi_menit')->default(30);
            $table->integer('porsi')->default(2);
            $table->enum('kesulitan', ['Mudah', 'Sedang', 'Sulit'])->default('Sedang');
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('jumlah_rating')->default(0);
            $table->integer('likes')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
