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
        // Create countries table
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

        // Create protocols table
        Schema::create('protocols', function (Blueprint $table) {
            $table->id();
            $table->string('module')->unique();
            $table->string('protocol')->nullable();
            $table->string('severity')->nullable();
            $table->text('description')->nullable();
            $table->string('modifier')->nullable();
            $table->timestamps();

            // Index for lookups
            $table->index('module');
        });

        // Create accounts table
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('entity')->unique();
            $table->string('sector')->nullable();
            $table->string('entity_country')->nullable();
            $table->string('url')->nullable();
            $table->string('point_of_contact')->nullable();
            $table->string('type_of_account')->nullable();
            $table->string('account_manager')->nullable();
            $table->timestamps();

            // Index for lookups
            $table->index('entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('protocols');
        Schema::dropIfExists('countries');
    }
};
