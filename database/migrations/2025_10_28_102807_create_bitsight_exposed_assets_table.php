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
        Schema::create('bitsight_exposed_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_id')->nullable()->constrained();
            $table->string('ip');
            $table->integer('port');
            $table->string('module')->nullable();
            $table->dateTime('detected_at');
            $table->text('raw_data');
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

            // Unique constraint to prevent duplicates
            $table->unique(['ip', 'port', 'detected_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitsight_exposed_assets');
    }
};
