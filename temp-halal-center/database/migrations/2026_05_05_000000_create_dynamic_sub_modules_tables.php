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
        Schema::create('dynamic_sub_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_item_id')->constrained('sector_items')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->json('fields'); // Array of { name, type, required, options }
            $table->string('custom_template_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['sector_item_id', 'slug']);
        });

        Schema::create('dynamic_sub_module_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_sub_module_id')->constrained('dynamic_sub_modules')->cascadeOnDelete();
            $table->foreignId('sector_item_id')->constrained('sector_items')->cascadeOnDelete();
            $table->json('data');
            $table->integer('row_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['dynamic_sub_module_id', 'row_order']);
            $table->index('sector_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_sub_module_data');
        Schema::dropIfExists('dynamic_sub_modules');
    }
};
