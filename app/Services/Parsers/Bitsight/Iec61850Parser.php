<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts device information from the IEC-61850 protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * Iec-61850 Key Frequency:
 * +------------+--------+------------+
 * | Key        | Count  | Percentage |
 * +------------+--------+------------+
 * | product    | 12,058 | 100%       |
 * | vendor     | 12,058 | 100%       |
 * | version    | 12,058 | 100%       |
 * | identifier | 7,134  | 59.16%     |
 * +------------+--------+------------+
 */
class Iec61850Parser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        $vendor = $this->extractNested(["iec-61850", "Iec-61850"], "vendor")
            ?? $this->extract(["Vendor", "vendor", "vendor_name"])
            ?? "unknown";
        $fingerprint = $this->extractNested(["iec-61850", "Iec-61850"], "product");
        $version = $this->extractNested(["iec-61850", "Iec-61850"], "version");

        $vendor = str_contains($vendor, "Unknown command") ? "unknown" : $vendor;
        $fingerprint = str_contains($fingerprint, "Unknown command") ? null : $fingerprint;
        $version = str_contains($version, "Unknown command") ? null : $version;

        return [
            new ParsedDeviceData(
                vendor: $vendor,
                fingerprint: $fingerprint,
                version: $version,
            ),
        ];
    }
}
