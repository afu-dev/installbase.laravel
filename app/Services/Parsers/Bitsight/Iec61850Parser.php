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

        $vendor = str_contains((string) $vendor, "Unknown command") || (str_contains((string) $vendor, "<") && str_contains((string) $vendor, ">")) ? "unknown" : $vendor;
        $fingerprint = $fingerprint !== null && (str_contains((string) $fingerprint, "Unknown command") || str_contains((string) $fingerprint, "<") && str_contains((string) $fingerprint, ">")) ? null : $fingerprint;
        $version = $version !== null && (str_contains((string) $version, "Unknown command") || (str_contains((string) $version, "<") && str_contains((string) $version, ">"))) ? null : $version;

        return [
            new ParsedDeviceData(
                vendor: preg_replace('/[^\x20-\x7E]/', '', str_replace("\n", " ", $vendor)),
                fingerprint: $fingerprint,
                version: $version,
            ),
        ];
    }
}
