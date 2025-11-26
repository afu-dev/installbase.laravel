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
        DB::statement('ALTER TABLE protocols MODIFY id BIGINT UNSIGNED NOT NULL');

        // Drop the existing primary key
        Schema::table('protocols', function (Blueprint $table) {
            $table->dropPrimary(['id']);
        });

        // Drop the id column
        Schema::table('protocols', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // Make protocol column NOT NULL
        Schema::table('protocols', function (Blueprint $table) {
            $table->string('protocol')->nullable(false)->change();
        });

        // Drop unique constraint on module and make it primary key
        Schema::table('protocols', function (Blueprint $table) {
            $table->dropUnique(['module']);
            $table->primary('module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop primary key from module
        Schema::table('protocols', function (Blueprint $table) {
            $table->dropPrimary(['module']);
        });

        // Make protocol column nullable again
        Schema::table('protocols', function (Blueprint $table) {
            $table->string('protocol')->nullable()->change();
        });

        // Re-add id column and unique constraint back to module
        Schema::table('protocols', function (Blueprint $table) {
            $table->id()->first();
            $table->unique('module');
        });
    }
};
