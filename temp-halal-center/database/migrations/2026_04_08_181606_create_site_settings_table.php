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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('institution_name');
            $table->string('institution_name_en')->nullable();
            $table->string('tagline');
            $table->string('tagline_en')->nullable();
            $table->text('short_description');
            $table->text('short_description_en')->nullable();
            $table->string('hero_badge')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('address')->nullable();
            $table->text('address_en')->nullable();
            $table->string('consultation_url')->nullable();
            $table->string('sehati_url')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('og_image_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('default_locale', 5)->default('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
