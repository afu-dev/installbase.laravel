<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('censys_exposed_assets', function (Blueprint $table) {
            // Index for filtering by execution
            $table->index('execution_id');

            // Composite index for execution + IP lookups
            $table->index(['execution_id', 'ip']);

            // Composite index for efficient keyset pagination with module filter
            $table->index(['module', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('censys_exposed_assets', function (Blueprint $table) {
            $table->dropUnique(['ip', 'port', 'detected_at']);
            $table->dropIndex(['execution_id']);
            $table->dropIndex(['execution_id', 'ip']);
            $table->dropIndex(['module', 'id']);
        });
    }
};
