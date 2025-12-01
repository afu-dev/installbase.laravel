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
            // Drop redundant single-column index (composite index covers this)
            $table->dropIndex(['module']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitsight_exposed_assets', function (Blueprint $table) {
            // Restore single-column index
            $table->index('module');
        });
    }
};
