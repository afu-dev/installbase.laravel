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
        DB::statement('ALTER TABLE countries MODIFY id BIGINT UNSIGNED NOT NULL');

        // Drop the existing primary key
        Schema::table('countries', function (Blueprint $table) {
            $table->dropPrimary(['id']);
        });

        // Drop the id column
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // Drop unique constraint on country_code2 and make it primary key
        Schema::table('countries', function (Blueprint $table) {
            $table->dropUnique(['country_code2']);
            $table->primary('country_code2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop primary key from country_code2
        Schema::table('countries', function (Blueprint $table) {
            $table->dropPrimary(['country_code2']);
        });

        // Re-add id column and unique constraint back to country_code2
        Schema::table('countries', function (Blueprint $table) {
            $table->id()->first();
            $table->unique('country_code2');
        });
    }
};
