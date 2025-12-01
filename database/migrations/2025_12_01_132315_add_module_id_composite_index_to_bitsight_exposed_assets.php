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
        Schema::table('bitsight_exposed_assets', function (Blueprint $table) {
            // Composite index for efficient keyset pagination with module filter
            // Supports queries like: WHERE module = X AND id > Y ORDER BY id
            $table->index(['module', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitsight_exposed_assets', function (Blueprint $table) {
            $table->dropIndex(['module', 'id']);
        });
    }
};
