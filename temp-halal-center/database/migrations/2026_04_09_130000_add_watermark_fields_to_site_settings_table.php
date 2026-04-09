<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->boolean('watermark_enabled')->default(false)->after('default_locale');
            $table->string('watermark_text')->nullable()->after('watermark_enabled');
            $table->string('watermark_image_path')->nullable()->after('watermark_text');
            $table->decimal('watermark_opacity', 3, 2)->default(0.18)->after('watermark_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'watermark_enabled',
                'watermark_text',
                'watermark_image_path',
                'watermark_opacity',
            ]);
        });
    }
};
