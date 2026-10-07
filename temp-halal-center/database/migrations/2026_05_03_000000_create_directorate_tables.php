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
        Schema::create('directorate_configs', function (Blueprint $table) {
            $table->id();
            $table->string('item_key')->unique();
            $table->string('sub_module');
            $table->string('group_prefix')->nullable();
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->json('columns');
            $table->string('custom_template_path')->nullable();
            $table->timestamps();
        });

        Schema::create('directorate_rows', function (Blueprint $table) {
            $table->id();
            $table->string('item_key')->index();
            $table->json('data');
            $table->integer('row_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directorate_rows');
        Schema::dropIfExists('directorate_configs');
    }
};
