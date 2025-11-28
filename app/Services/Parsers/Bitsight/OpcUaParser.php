<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts device information from the OPC-UA protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * Opc-ua Key Frequency:
 * +---------------+-------+------------+
 * | Key           | Count | Percentage |
 * +---------------+-------+------------+
 * | endpoints     | 9,780 | 64.93%     |
 * | server        | 7,815 | 51.89%     |
 * | child_servers | 5,063 | 33.61%     |
 * +---------------+-------+------------+
 */
class OpcUaParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        return [
            new ParsedDeviceData(
                vendor: 'not_parsed',
            ),
        ];
    }
}
