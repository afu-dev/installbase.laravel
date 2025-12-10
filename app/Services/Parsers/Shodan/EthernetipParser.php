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

        // check individually each values
        // we need to exclude when values are empty string or a string containing zero

        // Brand detection first, then fallback to existing vendor extraction
        $vendor = $this->detectBrand($this->rawData);
        if ($vendor === null) {
            $vendor = "unknown";
            if (!empty($ethernetipData["Vendor ID"])) {
                $vendor = $ethernetipData["Vendor ID"];
            }
        }

        $fingerprint = null;
        if (!empty($ethernetipData["Product name"])) {
            $fingerprint = $ethernetipData["Product name"];
        }

        $sn = null;
        if (!empty($ethernetipData["Serial number"]) && $ethernetipData["Serial number"] !== "0x00000000") {
            $sn = $ethernetipData["Serial number"];
        }

        return [
            new ParsedDeviceData(
                vendor: $vendor,
                fingerprint: $fingerprint,
                sn: $sn,
            ),
        ];
    }
}
