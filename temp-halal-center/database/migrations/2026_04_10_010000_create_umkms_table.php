<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->nullable()->unique()->comment('External ID from scraped source');
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lph_partner_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_umkm');
            $table->string('slug')->unique();
            $table->string('nama_pemilik')->nullable();
            $table->string('lembaga')->nullable();
            $table->string('kategori')->nullable();
            $table->string('provinsi')->nullable()->default('KALIMANTAN TIMUR');
            $table->string('kab_kota')->nullable();
            $table->text('alamat')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('nomor_wa')->nullable();
            $table->string('link_pembelian')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->string('foto_url')->nullable()->comment('External photo URL from scrape');
            $table->string('image_path')->nullable()->comment('Locally uploaded photo');
            $table->string('approval')->default('DISETUJUI');
            $table->string('status')->default('published');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('kategori');
            $table->index('kab_kota');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};
