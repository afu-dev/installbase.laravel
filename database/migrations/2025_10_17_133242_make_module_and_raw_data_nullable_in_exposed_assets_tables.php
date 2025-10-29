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
        Schema::table('shodan_exposed_assets', function (Blueprint $table) {
            $table->string('module')->nullable()->change();
            $table->text('raw_data')->nullable()->change();
        });

        Schema::table('censys_exposed_assets', function (Blueprint $table) {
            $table->string('module')->nullable()->change();
            $table->text('raw_data')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shodan_exposed_assets', function (Blueprint $table) {
            $table->string('module')->nullable(false)->change();
            $table->text('raw_data')->nullable(false)->change();
        });

        Schema::table('censys_exposed_assets', function (Blueprint $table) {
            $table->string('module')->nullable(false)->change();
            $table->text('raw_data')->nullable(false)->change();
        });
    }
};
