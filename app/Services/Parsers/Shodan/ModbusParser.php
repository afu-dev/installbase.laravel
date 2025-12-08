<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

class ModbusParser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        $modbusData = [];
        $units = explode("\n\n", trim($this->rawData));
        foreach ($units as $unit) {
            $lines = explode("\n-- ", trim($unit));
            $unitId = 0;
            foreach ($lines as $index => $line) {
                [$key, $value] = explode(':', (string) preg_replace('/[^\x20-\x7E]/', '', str_replace("\n", " ", $line)), 2);
                if ($index === 0) {
                    $unitId = trim($value);
                }
                $modbusData[$unitId][trim($key)] = trim($value);
            }
        }

        $devices = [];
        foreach ($modbusData as $unitId => $unitData) {
            ["vendor" => $vendor, "model" => $model, "version" => $version] = $this->extractDeviceInformations($unitData);
            if (empty($vendor)) {
                continue;
            }
            $devices[$unitId] = new ParsedDeviceData(
                vendor: $vendor,
                fingerprint: $model,
                version: $version,
                modbus_project_info: $unitData["Project information"] ?? null,
            );
        }

        return $devices;
    }

    /**
     * @param array $unitData
     * @return ?array{vendor: string, model: ?string, version: ?string}
     */
    private function extractDeviceInformations(array $unitData): ?array
    {
        if (empty($unitData["Device Identification"])) {
            return null;
        }

        $identificationParts = explode(" ", (string) $unitData["Device Identification"]);

        if (str_contains((string) $unitData["Device Identification"], 'Schneider Electric')) {
            $version = array_pop($identificationParts);
            array_shift($identificationParts);
            array_shift($identificationParts);
            return ["vendor" => "Schneider Electric", "model" => implode(" ", $identificationParts), "version" => $version];
        }

        $vendor = array_shift($identificationParts);
        $version = array_pop($identificationParts);

        return ["vendor" => $vendor, "model" => implode(" ", $identificationParts), "version" => $version];
    }
}
