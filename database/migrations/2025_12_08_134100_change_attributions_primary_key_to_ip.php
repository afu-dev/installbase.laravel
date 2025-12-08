<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Modify the id column to remove AUTO_INCREMENT
        DB::statement('ALTER TABLE attributions MODIFY id BIGINT UNSIGNED NOT NULL');

        Schema::table('attributions', function (Blueprint $table) {
            // Step 2: Drop the primary key on id
            $table->dropPrimary(['id']);

            // Step 3: Drop the id column
            $table->dropColumn('id');

            // Step 4: Drop the unique constraint on ip
            $table->dropUnique(['ip']);

            // Step 5: Drop the existing index on ip
            $table->dropIndex(['ip']);

            // Step 6: Make ip the primary key
            $table->primary('ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attributions', function (Blueprint $table) {
            // Drop the primary key on ip
            $table->dropPrimary(['ip']);

            // Add back the id column as primary key (must be first)
            $table->id()->first();

            // Add back the unique constraint on ip
            $table->unique('ip');

            // Add back the index on ip
            $table->index('ip');
        });
    }
};
