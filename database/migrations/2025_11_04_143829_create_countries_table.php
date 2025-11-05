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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('country_code2', 2)->unique();
            $table->string('country_code3', 3)->nullable();
            $table->string('country');
            $table->string('region')->nullable();
            $table->string('ciso_region')->nullable();
            $table->string('ciso_zone')->nullable();
            $table->string('operation_zone')->nullable();
            $table->timestamps();

            // Index for lookups
            $table->index('country_code2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
