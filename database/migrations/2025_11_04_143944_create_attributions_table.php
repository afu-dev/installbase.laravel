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
        Schema::create('attributions', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->unique();
            $table->string('entity')->nullable();
            $table->string('sector')->nullable();
            $table->string('domain')->nullable();
            $table->text('hostnames')->nullable();
            $table->string('isp')->nullable();
            $table->string('asn')->nullable();
            $table->text('whois')->nullable();
            $table->string('city')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('source_of_attribution')->nullable();
            $table->dateTime('last_exposure_at')->nullable();
            $table->timestamps();

            // Indexes for lookups
            $table->index('ip');
            $table->index('entity');
            $table->index('country_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributions');
    }
};
