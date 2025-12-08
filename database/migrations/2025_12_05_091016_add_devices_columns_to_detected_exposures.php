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
        Schema::table('detected_exposures', function (Blueprint $table) {
            $table->string("vendor", 100)->nullable(false)->after("module");
            $table->text("version")->nullable()->after("vendor");
            $table->text("fingerprint")->nullable()->after("version");
            $table->text("sn")->nullable()->after("fingerprint");
            $table->text("device_mac")->nullable()->after("sn");
            $table->text("modbus_project_info")->nullable()->after("device_mac");
            $table->text("opc-ua_security_policy")->nullable()->after("modbus_project_info");
            $table->text("is_guest_account_active")->nullable()->after("opc-ua_security_policy");
            $table->text("registration_info")->nullable()->after("is_guest_account_active");
            $table->text("secure_power_app")->nullable()->after("registration_info");
            $table->text("nmc_card_number")->nullable()->after("secure_power_app");
            $table->text("fingerprint_raw")->nullable()->after("nmc_card_number");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detected_exposures', function (Blueprint $table) {
            $table->dropColumn("vendor");
            $table->dropColumn("version");
            $table->dropColumn("fingerprint");
            $table->dropColumn("sn");
            $table->dropColumn("device_mac");
            $table->dropColumn("modbus_project_info");
            $table->dropColumn("opc-ua_security_policy");
            $table->dropColumn("is_guest_account_active");
            $table->dropColumn("registration_info");
            $table->dropColumn("secure_power_app");
            $table->dropColumn("nmc_card_number");
            $table->dropColumn("fingerprint_raw");
        });
    }
};
