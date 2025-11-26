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
        Schema::table('countries', function (Blueprint $table) {
            $table->string('country_code3', 3)->nullable(false)->change();
            $table->string('region')->nullable(false)->change();
            $table->string('ciso_region')->nullable(false)->change();
            $table->string('ciso_zone')->nullable(false)->change();
            $table->string('operation_zone')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('country_code3', 3)->nullable()->change();
            $table->string('region')->nullable()->change();
            $table->string('ciso_region')->nullable()->change();
            $table->string('ciso_zone')->nullable()->change();
            $table->string('operation_zone')->nullable()->change();
        });
    }
};
