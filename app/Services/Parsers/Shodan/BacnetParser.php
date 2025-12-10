<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * Bacnet Key Frequency:
 * +----------------------+-------+------------+
 * | Key                  | Count | Percentage |
 * +----------------------+-------+------------+
 * | Instance ID          | 122   | 100%       |
 * | Object Name          | 122   | 100%       |
 * | Vendor Name          | 122   | 100%       |
 * | Application Software | 122   | 100%       |
 * | Firmware             | 122   | 100%       |
 * | Model Name           | 122   | 100%       |
 * | Description          | 122   | 100%       |
 * +----------------------+-------+------------+
 */
class BacnetParser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        $bacnetData = [];
        $lines = explode("\n", trim(str_replace("\r\n", "\t\t", $this->rawData)));
        foreach ($lines as $line) {
            if (empty($line) || !str_contains($line, ":")) {
                continue;
            }

            [$key, $value] = explode(":", $line, 2);
            $bacnetData[trim($key)] = trim($value);
        }

        // Brand detection first, then fallback to existing vendor extraction
        $vendor = $this->detectBrand($this->rawData);
        if ($vendor === null) {
            $vendor = $bacnetData["Vendor Name"] ?? "unknown";
        }

        return [
            new ParsedDeviceData(
                vendor: $vendor,
                fingerprint: $bacnetData["Model Name"] ?? null,
                version: $bacnetData["Firmware"] ?? null,
            ),
        ];
    }
}
