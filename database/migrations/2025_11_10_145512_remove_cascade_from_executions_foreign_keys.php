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
        Schema::table('executions', function (Blueprint $table) {
            // Drop existing foreign keys with cascade
            $table->dropForeign(['scan_id']);
            $table->dropForeign(['query_id']);

            // Recreate foreign keys without cascade (restrict on delete)
            $table->foreign('scan_id')->references('id')->on('scans')->restrictOnDelete();
            $table->foreign('query_id')->references('id')->on('queries')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('executions', function (Blueprint $table) {
            // Drop non-cascade foreign keys
            $table->dropForeign(['scan_id']);
            $table->dropForeign(['query_id']);

            // Recreate with cascade (original behavior)
            $table->foreign('scan_id')->references('id')->on('scans')->cascadeOnDelete();
            $table->foreign('query_id')->references('id')->on('queries')->cascadeOnDelete();
        });
    }
};
