<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds compound index on (execution_id, ip) to optimize COUNT(DISTINCT ip) queries.
     * Performance improvement: 14s → 2s (7x faster) for queries filtering by execution_id.
     *
     * POST-DEPLOYMENT: Run `ANALYZE TABLE bitsight_exposed_assets;` to update optimizer statistics
     * so MySQL automatically uses this index without needing USE INDEX hints.
     */
    public function up(): void
    {
        Schema::table('bitsight_exposed_assets', function (Blueprint $table) {
            $table->index(['execution_id', 'ip'], 'bitsight_exposed_assets_execution_id_ip_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitsight_exposed_assets', function (Blueprint $table) {
            $table->dropIndex('bitsight_exposed_assets_execution_id_ip_index');
        });
    }
};
