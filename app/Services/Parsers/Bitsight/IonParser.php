<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts device information from the ION protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * Ion Key Frequency:
 * +----------------------+--------+------------+
 * | Key                  | Count  | Percentage |
 * +----------------------+--------+------------+
 * | compliance           | 36,077 | 99.19%     |
 * | ion_version          | 36,075 | 99.19%     |
 * | secure_ion           | 36,038 | 99.08%     |
 * | device_namespace     | 35,976 | 98.91%     |
 * | device_type          | 35,909 | 98.73%     |
 * | revision             | 35,909 | 98.73%     |
 * | serial_num           | 35,909 | 98.73%     |
 * | device_name          | 35,814 | 98.47%     |
 * | template             | 35,785 | 98.39%     |
 * | mac_address          | 35,472 | 97.53%     |
 * | options              | 32,044 | 88.1%      |
 * | metering_fw_revision | 15,061 | 41.41%     |
 * | tag1                 | 14,903 | 40.97%     |
 * | owner                | 14,689 | 40.39%     |
 * | tag2                 | 13,786 | 37.9%      |
 * +----------------------+--------+------------+
 */
class IonParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        $vendor = $this->detectBrand($this->extract(["Ion", "ion"]))
            ?? $this->extract(["Vendor", "vendor", "vendor_name"])
            ?? "unknown";

        return [
            new ParsedDeviceData(
                vendor: $this->detectBrand($vendor) ?? $vendor,
                fingerprint: $this->extractNested(["Ion", "ion"], "device_type"),
                version: $this->extractNested(["Ion", "ion"], "revision"),
                sn: $this->extractNested(["Ion", "ion"], "serial_num"),
                device_mac: $this->extractNested(["Ion", "ion"], "mac_address"),
                fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"]),
            ),
        ];
    }
}
