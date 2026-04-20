<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->string('kecamatan')->nullable()->after('kab_kota');
            $table->string('kelurahan')->nullable()->after('kecamatan');
        });

        Schema::table('halal_locations', function (Blueprint $table) {
            $table->string('kecamatan')->nullable()->after('city_name');
            $table->string('kelurahan')->nullable()->after('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kelurahan']);
        });

        Schema::table('halal_locations', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kelurahan']);
        });
    }
};
