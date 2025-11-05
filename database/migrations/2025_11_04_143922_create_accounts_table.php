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
    }
};
