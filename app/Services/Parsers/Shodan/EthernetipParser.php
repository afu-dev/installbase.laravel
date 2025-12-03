<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * +---------------+-------+------------+
 * | Key           | Count | Percentage |
 * +---------------+-------+------------+
 * | Product name  | 53    | 100%       |
 * | Vendor ID     | 53    | 100%       |
 * | Serial number | 53    | 100%       |
 * | Device type   | 53    | 100%       |
 * | Device IP     | 53    | 100%       |
 * +---------------+-------+------------+
 */
class EthernetipParser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        $ethernetipData = [];
        $lines = explode("\n", trim($this->rawData));
        foreach ($lines as $line) {
            [$key, $value] = explode(':', $line, 2);
            $ethernetipData[trim($key)] = trim($value);
        }

        return [
            new ParsedDeviceData(
                vendor: $ethernetipData["Vendor ID"] ?? "unknown",
                fingerprint: $ethernetipData["Product name"] ?? null,
                sn: $ethernetipData["Serial number"] ?? null,
            ),
        ];
    }
}
