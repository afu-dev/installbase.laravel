<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * Iec-61850 Key Frequency:
 * +---------+-------+------------+
 * | Key     | Count | Percentage |
 * +---------+-------+------------+
 * | Vendor  | 86    | 100%       |
 * | Model   | 86    | 100%       |
 * | Version | 86    | 100%       |
 * +---------+-------+------------+
 */
class Iec61850Parser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        $iecData = [];
        $lines = explode("\n", trim($this->rawData));
        foreach ($lines as $line) {
            [$key, $value] = explode(':', $line, 2);
            $iecData[trim($key)] = trim($value);
        }

        return [
            new ParsedDeviceData(
                vendor: $iecData["Vendor"] ?? "unknown",
                fingerprint: $iecData["Model"] ?? null,
                version: $iecData["Version"] ?? null,
            ),
        ];
    }
}
