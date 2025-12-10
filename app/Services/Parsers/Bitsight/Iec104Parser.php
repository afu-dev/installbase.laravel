<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts device information from the IEC-104 protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * Iec-104 Key Frequency:
 * +------------------------------+---------+------------+
 * | Key                          | Count   | Percentage |
 * +------------------------------+---------+------------+
 * | common_addresses             | 204,834 | 100%       |
 * | information_object_addresses | 153,803 | 75.09%     |
 * +------------------------------+---------+------------+
 */
class Iec104Parser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        $vendor = $this->detectBrand($this->extract(["iec-104", "Iec-104"]))
            ?? $this->extract(["Vendor", "vendor", "vendor_name"])
            ?? "unknown";

        return [
            new ParsedDeviceData(
                vendor: $this->detectBrand($vendor) ?? $vendor,
            ),
        ];
    }
}
