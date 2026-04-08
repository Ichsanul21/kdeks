<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('halal_locations', function (Blueprint $table) {
            $table->foreignId('lph_partner_id')->nullable()->after('region_id')->constrained()->nullOnDelete();
            $table->string('city_name')->nullable()->after('category');
            $table->string('business_scale')->nullable()->after('city_name');
        });
    }

    public function down(): void
    {
        Schema::table('halal_locations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lph_partner_id');
            $table->dropColumn(['city_name', 'business_scale']);
        });
    }
};
