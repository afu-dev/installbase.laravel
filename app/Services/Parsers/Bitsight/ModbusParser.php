<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * Modbus Key Frequency:
 * +-----+-----------+------------+
 * | Key | Count     | Percentage |
 * +-----+-----------+------------+
 * | 0   | 5,512,002 | 100%       |
 * | 1   | 4,651,586 | 84.39%     |
 * | 2   | 3,435,384 | 62.33%     |
 * +-----+-----------+------------+
 */
class ModbusParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        $devices = [];
        $modbusData = $this->extractJson(["Modbus", "modbus"]);

        if (empty($modbusData)) {
            // No Modbus data - return single device with root-level vendor
            return [new ParsedDeviceData(
                vendor: $this->extract(["Vendor", "vendor"]) ?? "Unknown",
                fingerprint: $this->extractFingerprintFromJson(),
                fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"]),
            )];
        }

        foreach ($modbusData as $index => $device) {
            $uid = $device["uid"] ?? $index;

            // Skip devices with errors or error messages in device_identification
            if (isset($device["error"])
                || !isset($device["device_identification"])
                || stripos($device["device_identification"], "error") !== false) {
                continue;
            }

            $parsedInfo = $this->parseDeviceIdentification(
                $device["device_identification"],
                $device["cpu_module"] ?? null
            );

            // Vendor: parsed from device_identification → root Vendor/vendor → "Unknown"
            $vendor = $parsedInfo["vendor"]
                ?? $this->extract(["Vendor", "vendor"])
                ?? "Unknown";

            // Fingerprint priority: root Fingerprint JSON → explicit cpu_module field → parsed cpu
            $fingerprint = $this->extractFingerprintFromJson()
                ?? $device["cpu_module"]
                ?? $parsedInfo["cpu"];

            // Version: parsed from device_identification
            $version = $parsedInfo["version"];

            $devices[$uid] = new ParsedDeviceData(
                vendor: $vendor,
                fingerprint: $fingerprint,
                version: $version,
                modbus_project_info: $device["project_information"] ?? null,
                fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"]),
            );
        }

        // If no valid devices were found, return single device with root-level vendor
        if (empty($devices)) {
            return [new ParsedDeviceData(
                vendor: $this->extract(["Vendor", "vendor"]) ?? "Unknown",
                fingerprint: $this->extractFingerprintFromJson(),
                fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"]),
            )];
        }

        return $devices;
    }

    /**
     * Parse device_identification string to extract vendor, cpu_module, and version.
     *
     * Expected format: "Vendor CPU_Module vVersion"
     * Examples:
     *   - "TELEMECANIQUE TWDLCAE40DRF 05.40"
     *   - "Schneider Electric TM251MESE V04.00.06.38"
     *   - "Schneider Electric BMX P34 2020 v2.4"
     *
     * @param string|null $cpuModule Known CPU module (if available) to help parse
     * @return array{vendor: string|null, cpu: string|null, version: string|null}
     */
    private function parseDeviceIdentification(string $deviceIdentification, ?string $cpuModule = null): array
    {
        $info = ["vendor" => null, "cpu" => null, "version" => null];

        // Skip error messages
        if (stripos($deviceIdentification, "error") !== false) {
            return $info;
        }

        // If cpu_module is known, use it to split device_identification
        if ($cpuModule && str_contains($deviceIdentification, $cpuModule)) {
            $parts = explode($cpuModule, $deviceIdentification, 2);

            // Vendor is everything before cpu_module
            $info["vendor"] = trim($parts[0]);
            $info["cpu"] = $cpuModule;

            // Version is after cpu_module (extract digits with optional v/V prefix)
            if (isset($parts[1]) && preg_match('/[vV]?(\d+(?:\.\d+)*)/', $parts[1], $versionMatch)) {
                $info["version"] = $versionMatch[1];
            }

            return $info;
        }

        // Fallback: regex-based parsing when cpu_module not available
        // Pattern 1: Try "Schneider Electric" first (known two-word vendor)
        if (preg_match('/^\s*(Schneider\s+Electric)\s+(.+?)\s+[vV]?(\d+(?:\.\d+)*)\s*$/i', $deviceIdentification, $matches)) {
            $info["vendor"] = trim($matches[1]);
            $info["cpu"] = trim($matches[2]);
            $info["version"] = trim($matches[3]);
            return $info;
        }

        // Pattern 2: Single-word vendor (e.g., "TELEMECANIQUE")
        if (preg_match('/^\s*([A-Za-z]+)\s+(.+?)\s+[vV]?([A-Z0-9]+(?:\.[A-Z0-9]+)*)\s*$/i', $deviceIdentification, $matches)) {
            $info["vendor"] = trim($matches[1]);
            $info["cpu"] = trim($matches[2]);
            $info["version"] = trim($matches[3]);
        }

        return $info;
    }

    /**
     * Extract fingerprint value from root-level Fingerprint JSON field.
     */
    private function extractFingerprintFromJson(): ?string
    {
        $fingerprintJson = $this->extractJson(["Fingerprint", "fingerprint"]);

        if (!empty($fingerprintJson) && isset($fingerprintJson[0]["fingerprint"])) {
            return $fingerprintJson[0]["fingerprint"];
        }

        return null;
    }
}
