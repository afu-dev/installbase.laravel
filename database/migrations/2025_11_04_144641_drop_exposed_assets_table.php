<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drop the deprecated exposed_assets table in favor of normalized schema:
     * - detected_exposures (IP+Port detection data)
     * - attributions (IP attribution/context data)
     */
    public function up(): void
    {
        // Drop PostgreSQL unique index if it exists
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS exposed_assets_ip_port_unique');
        }

        Schema::dropIfExists('exposed_assets');
    }

    /**
     * Reverse the migrations.
     *
     * Note: This recreates the old schema for rollback purposes only.
     * You should not roll back this migration in production.
     */
    public function down(): void
    {
        Schema::create('exposed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->integer('port');
            $table->string('module')->nullable();
            $table->string('transport');
            $table->dateTime('first_detected_at');
            $table->dateTime('last_detected_at');
            $table->text('hostnames')->nullable();
            $table->string('entity')->nullable();
            $table->string('isp')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('os')->nullable();
            $table->string('asn')->nullable();
            $table->string('product')->nullable();
            $table->string('product_sn')->nullable();
            $table->string('version')->nullable();
            $table->text('raw_data')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('ip');
            $table->index(['ip', 'port']);
            $table->index('first_detected_at');
            $table->index('last_detected_at');
            $table->index('deleted_at');
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX exposed_assets_ip_port_unique ON exposed_assets(ip, port) WHERE deleted_at IS NULL');
        }
    }
};
