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
        Schema::table('bitsight_exposed_assets', function (Blueprint $table) {
            $table->longText('raw_data')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitsight_exposed_assets', function (Blueprint $table) {
            $table->text('raw_data')->change();
        });
    }
};
