<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class ModbusParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        // vendor: in bitsight & shodan: extract from device identification
        //if cpu module value != NULL then schneider electric
        //in censys: vendor
        //
        //if value in brands then schneider electric

        $deviceIdentifications = [];
        $modbus = $this->extractJson("Modbus") ?: $this->extractJson("modbus");
        if (!empty($modbus)) {
            foreach ($modbus as $device) {
                $deviceIdentifications[] = $this->parseDevice($device);
            }
        }

        if (isset($deviceIdentifications[0])) {
            $vendor = $deviceIdentifications[0]["vendor"] ?? null;
            $version = $deviceIdentifications[0]["version"] ?? null;
        }

        // @todo: use $deviceIdentifications array to insert multiple detected exposure?

        // Default to Schneider Electric for modbus if vendor not found
        $extractedVendor = $vendor ?? $this->extract(["Vendor", "vendor", "vendor_name"]);

        // @TODO: RETURN ONE PARSED DEVICE PER UNIT ID
        return [new ParsedDeviceData(
            vendor: $extractedVendor ?: "Schneider Electric",
            fingerprint: $this->extract("device_type"),
            version: $version ?? $this->extract("revision"),
            sn: $this->extract("serial_num"),
            device_mac: $this->extract("mac_address"),
            fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"]),
        )];
    }

    private function parseDevice(array $device): array
    {
        $deviceInfo = [];

        $matches = [];
        if (isset($device["device_identification"])) {
            preg_match('/^\s*(Schneider Electric)\s+([A-Z0-9 ]+?)\s+([vV]\d+(?:\.\d+)*)/i', $device["device_identification"], $matches);
        }

        if (!empty($matches[1])) {
            $deviceInfo["vendor"] = $matches[1];
        }
        if (!empty($matches[2])) {
            $deviceInfo["cpu"] = $matches[2];
        }
        if (!empty($matches[3])) {
            $deviceInfo["version"] = $matches[3];
        }

        if (isset($device["uid"]) && is_numeric($device["uid"])) {
            $deviceInfo["uid"] = (int)$device["uid"];
        }

        if (!empty($device["project_information"])) {
            $deviceInfo["project_information"] = $device["project_information"];
        }

        return $deviceInfo;
    }
}
