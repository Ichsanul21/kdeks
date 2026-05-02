<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('sector_item_id')->nullable()->constrained('sector_items')->nullOnDelete();
        });

        // Create Admin Direktorat role if it doesn't exist
        if (!Role::where('name', 'AdminDirektorat')->exists()) {
            Role::create(['name' => 'AdminDirektorat', 'guard_name' => 'web']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sector_item_id']);
            $table->dropColumn('sector_item_id');
        });

        Role::where('name', 'AdminDirektorat')->delete();
    }
};
