<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detected_exposures', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->integer('port');
            $table->string('transport');
            $table->string('module')->nullable();
            $table->dateTime('first_detected_at');
            $table->dateTime('last_detected_at');
            $table->softDeletes();
            $table->timestamps();

            // Standard indexes for common queries
            $table->index('ip');
            $table->index(['ip', 'port']);
            $table->index('module');
            $table->index('first_detected_at');
            $table->index('last_detected_at');
            $table->index('deleted_at');
        });

        // PostgreSQL partial unique index for active (non-deleted) IP:Port combinations
        // SQLite doesn't support WHERE clauses in indexes, but local dev doesn't need strict uniqueness
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX detected_exposures_ip_port_unique ON detected_exposures(ip, port) WHERE deleted_at IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detected_exposures');
    }
};
