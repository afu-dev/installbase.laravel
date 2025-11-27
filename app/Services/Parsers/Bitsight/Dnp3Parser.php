<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * Should only return one device per detection.
 *
 * ---
 *
 * Dnp3 Key Frequency:
 * +---------------------+---------+------------+
 * | Key                 | Count   | Percentage |
 * +---------------------+---------+------------+
 * | source_address      | 116,585 | 100%       |
 * | destination_address | 116,585 | 100%       |
 * | control_code        | 116,367 | 99.81%     |
 * | status              | 218     | 0.19%      |
 * | device_manufacturer | 207     | 0.18%      |
 * | device_model        | 203     | 0.17%      |
 * | dnp3_conformance    | 192     | 0.16%      |
 * | firmware_version    | 156     | 0.13%      |
 * | hardware_version    | 154     | 0.13%      |
 * | device_id_code      | 146     | 0.13%      |
 * | device_location     | 145     | 0.12%      |
 * | device_name         | 144     | 0.12%      |
 * | serial_number       | 30      | 0.03%      |
 * +---------------------+---------+------------+
 */
class Dnp3Parser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        return [
            new ParsedDeviceData(
                vendor: $this->extractNested(["Dnp3", "dnp3"], "device_manufacturer", "Unknown"),
                fingerprint: $this->extractNested(["Dnp3", "dnp3"], "device_model"),
                version: $this->extractNested(["Dnp3", "dnp3"], ["firmware_version"]),
                sn: $this->extractNested(["Dnp3", "dnp3"], ["hardware_version"]),
            ),
        ];
    }
}
