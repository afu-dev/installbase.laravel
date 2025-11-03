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
        Schema::create('exposed_assets', function (Blueprint $table) {
            $table->id();

            // Core identifier fields
            $table->string('ip');
            $table->integer('port');

            // Service/module information (from priority vendor)
            $table->string('module')->nullable();
            $table->string('transport');

            // Detection timestamps
            $table->dateTime('first_detected_at');
            $table->dateTime('last_detected_at');

            // Network/location fields (from priority vendor: Bitsight > Shodan > Censys)
            $table->text('hostnames')->nullable(); // Combined/deduplicated from all vendors
            $table->string('entity')->nullable();
            $table->string('isp')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('os')->nullable();
            $table->string('asn')->nullable();

            // Product information (from priority vendor)
            $table->string('product')->nullable();
            $table->string('product_sn')->nullable();
            $table->string('version')->nullable();

            // Raw data from priority vendor (optional - vendor tables remain source of truth)
            $table->text('raw_data')->nullable();

            // Soft deletes for 90-day aging logic
            $table->softDeletes();

            $table->timestamps();

            // Standard indexes for common queries
            $table->index('ip');
            $table->index(['ip', 'port']);
            $table->index('first_detected_at');
            $table->index('last_detected_at');
            $table->index('deleted_at');
        });

        // PostgreSQL partial unique index for active (non-deleted) IP:Port combinations
        // SQLite doesn't support WHERE clauses in indexes, but local dev doesn't need strict uniqueness
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX exposed_assets_ip_port_unique ON exposed_assets(ip, port) WHERE deleted_at IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exposed_assets');
    }
};
