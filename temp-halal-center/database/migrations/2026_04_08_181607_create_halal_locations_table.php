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
        Schema::create('halal_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location_type')->default('umkm');
            $table->string('category');
            $table->string('owner_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('product_name')->nullable();
            $table->text('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website_url')->nullable();
            $table->longText('description')->nullable();
            $table->string('certificate_number')->nullable();
            $table->date('certificate_issued_at')->nullable();
            $table->date('certificate_expires_at')->nullable();
            $table->string('image_path')->nullable();
            $table->string('status')->default('published');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halal_locations');
    }
};
