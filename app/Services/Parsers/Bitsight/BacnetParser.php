<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * +-------------+-----------+------------+
 * | Key         | Count     | Percentage |
 * +-------------+-----------+------------+
 * | object      | 2,008,084 | 95.17%     |
 * | firmware    | 2,007,781 | 95.16%     |
 * | model       | 2,006,871 | 95.12%     |
 * | name        | 2,006,672 | 95.11%     |
 * | instance_id | 1,958,517 | 92.82%     |
 * | appsoft     | 1,845,837 | 87.48%     |
 * | desc        | 1,292,557 | 61.26%     |
 * | location    | 1,048,224 | 49.68%     |
 * | error       | 100,024   | 4.74%      |
 * +-------------+-----------+------------+
 */
class BacnetParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        $vendor = $this->extractNested(["Bacnet", "bacnet"], ["Name", "name"]);
        if ($vendor === null && $this->extractNested(["Bacnet", "bacnet"], ["Error", "error"])) {
            $vendor = "Error";
        }

        return [
            new ParsedDeviceData(
                vendor: $vendor,
                fingerprint: $this->extractNested(["Bacnet", "bacnet"], ["Model", "model"]),
                version: $this->extractNested(["Bacnet", "bacnet"], ["Firmware", "firmware"]),
                fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"])
            ),
        ];
    }
}
