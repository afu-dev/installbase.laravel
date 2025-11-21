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
        // Create censys field configurations table
        Schema::create('censys_field_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('protocol');
            $table->text('fields');
        });

        // Create censys exposed assets table with nullable fields from start
        Schema::create('censys_exposed_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_id');
            $table->string('ip');
            $table->integer('port');
            $table->string('module')->nullable();
            $table->dateTime('detected_at');
            $table->text('raw_data')->nullable();
            $table->text('hostnames')->nullable();
            $table->string('entity')->nullable();
            $table->string('isp')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('os')->nullable();
            $table->string('asn')->nullable();
            $table->string('transport')->nullable();
            $table->string('product')->nullable();
            $table->string('product_sn')->nullable();
            $table->string('version')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censys_exposed_assets');
        Schema::dropIfExists('censys_field_configurations');
    }
};
