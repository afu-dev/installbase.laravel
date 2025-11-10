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
        // Create queries table
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->string('product');
            $table->string('protocol')->nullable();
            $table->string('query');
            $table->string('query_type')->nullable();
            $table->string('vendor');
            $table->timestamps();
        });

        // Create scans table
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // Create executions table with all fields from start
        Schema::create('executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scan_id')->references('id')->on('scans')->restrictOnDelete();
            $table->foreignId('query_id')->references('id')->on('queries')->restrictOnDelete();
            $table->string('source_file')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->integer('count')->default(0);
            $table->boolean('errored')->default(false);
            $table->timestamps();

            $table->index('source_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('executions');
        Schema::dropIfExists('scans');
        Schema::dropIfExists('queries');
    }
};
