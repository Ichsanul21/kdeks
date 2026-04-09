<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('umkms', function (Blueprint $table): void {
            $table->unsignedInteger('nomor')->nullable()->after('source_id');
            $table->string('detail_url', 500)->nullable()->after('alamat');
            $table->string('edit_url', 500)->nullable()->after('detail_url');
            $table->unsignedInteger('jumlah_produk')->nullable()->after('image_path');

            $table->index('nomor');
            $table->index('jumlah_produk');
        });

        Schema::table('umkm_produks', function (Blueprint $table): void {
            $table->unsignedInteger('nomor')->nullable()->after('umkm_id');
            $table->string('detail_url', 500)->nullable()->after('slug');
            $table->string('edit_url', 500)->nullable()->after('detail_url');

            $table->index('nomor');
        });
    }

    public function down(): void
    {
        Schema::table('umkm_produks', function (Blueprint $table): void {
            $table->dropIndex(['nomor']);
            $table->dropColumn(['nomor', 'detail_url', 'edit_url']);
        });

        Schema::table('umkms', function (Blueprint $table): void {
            $table->dropIndex(['nomor']);
            $table->dropIndex(['jumlah_produk']);
            $table->dropColumn(['nomor', 'detail_url', 'edit_url', 'jumlah_produk']);
        });
    }
};
