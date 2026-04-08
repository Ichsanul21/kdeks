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
        Schema::create('halal_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand_name')->nullable();
            $table->string('category');
            $table->string('certificate_number')->nullable();
            $table->date('certificate_issued_at')->nullable();
            $table->date('certificate_expires_at')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('image_path')->nullable();
            $table->string('product_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halal_products');
    }
};
