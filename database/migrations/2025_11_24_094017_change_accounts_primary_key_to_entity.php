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
        // Remove auto-increment from id column first
        DB::statement('ALTER TABLE accounts MODIFY id BIGINT UNSIGNED NOT NULL');

        // Drop the existing primary key
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropPrimary(['id']);
        });

        // Drop the id column
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // Drop unique constraint on entity and make it primary key
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropUnique(['entity']);
            $table->primary('entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop primary key from entity
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropPrimary(['entity']);
        });

        // Re-add id column and unique constraint back to entity
        Schema::table('accounts', function (Blueprint $table) {
            $table->id()->first();
            $table->unique('entity');
        });
    }
};
