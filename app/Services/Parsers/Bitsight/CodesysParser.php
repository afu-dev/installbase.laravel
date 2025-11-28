<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts device information from the Codesys protocol data.
 *
 * ---
 *
 * Can return multiple devices per detection when devices array is present.
 *
 * ---
 *
 * Codesys Key Frequency:
 * +-----------------+---------+------------+
 * | Key             | Count   | Percentage |
 * +-----------------+---------+------------+
 * | codesys_version | 176,852 | 90.75%     |
 * | os              | 141,336 | 72.52%     |
 * | os_details      | 141,336 | 72.52%     |
 * | product         | 140,661 | 72.18%     |
 * | devices         | 53,463  | 27.43%     |
 * +-----------------+---------+------------+
 */
class CodesysParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        $devices = [];

        $codesysData = $this->extractJson(["Codesys", "codesys"]);
        if (!empty($codesysData["devices"])) {
            foreach ($codesysData["devices"] as $index => $codesysDatum) {
                $deviceId = trim(explode("@", $codesysDatum["node_name"])[1] ?? $index);
                $devices[$deviceId] = new ParsedDeviceData(
                    vendor: $codesysDatum["vendor_name"] ?? "Unknown",
                    fingerprint: $codesysDatum["device_name"] ?? null,
                    version: $codesysDatum["firmware_version"] ?? null,
                    sn: $codesysDatum["serial_nr"] ?? null,
                    fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"]),
                );
            }
        } else {
            return [
                new ParsedDeviceData(
                    vendor: "Unknown",
                    fingerprint: $codesysData["product"] ?? null,
                    version: $codesysData["os_details"] ?? null,
                ),
            ];
        }

        return $devices;
    }
}
