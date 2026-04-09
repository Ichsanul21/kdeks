<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkm_produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkms')->cascadeOnDelete();
            $table->string('nama_produk');
            $table->string('slug')->unique();
            $table->string('foto_url')->nullable()->comment('External photo URL from scrape');
            $table->string('image_path')->nullable()->comment('Locally uploaded photo');
            $table->string('harga')->nullable();
            $table->string('lph_lp3h')->nullable();
            $table->string('akta_halal')->nullable();
            $table->string('tahun_terbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->index('umkm_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm_produks');
    }
};
