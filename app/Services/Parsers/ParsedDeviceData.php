<?php

namespace App\Services\Parsers;

readonly class ParsedDeviceData
{
    public function __construct(
        public string  $vendor,
        public ?string $fingerprint = null,
        public ?string $version = null,
        public ?string $sn = null,
        public ?string $device_mac = null,
        public ?string $modbus_project_info = null,
        public ?string $opc_ua_security_policy = null,
        public ?bool   $is_guest_account_active = null,
        public ?string $registration_info = null,
        public ?string $secure_power_app = null,
        public ?string $nmc_card_num = null,
        public ?array  $fingerprint_raw = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            "vendor" => $this->vendor,
            "fingerprint" => $this->fingerprint,
            "version" => $this->version,
            "sn" => $this->sn,
            "device_mac" => $this->device_mac,
            "modbus_project_info" => $this->modbus_project_info,
            "opc-ua_security_policy" => $this->opc_ua_security_policy,
            "is_guest_account_active" => $this->is_guest_account_active,
            "registration_info" => $this->registration_info,
            "secure_power_app" => $this->secure_power_app,
            "nmc_card_num" => $this->nmc_card_num,
            "fingerprint_raw" => $this->fingerprint_raw,
        ];
    }
}
