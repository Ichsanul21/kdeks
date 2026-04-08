<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sehati_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lph_partner_id')->nullable()->constrained()->nullOnDelete();
            $table->string('owner_name');
            $table->string('business_name');
            $table->string('product_name');
            $table->string('phone');
            $table->text('description')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sehati_registrations');
    }
};
