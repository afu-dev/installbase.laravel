<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 *  This parser extracts device information from the KNX protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * +--------------------------+-----------+------------+
 * | Key                      | Count     | Percentage |
 * +--------------------------+-----------+------------+
 * | device_friendly_name     | 1,285,720 | 100%       |
 * | device_mac               | 1,285,720 | 100%       |
 * | device_serial            | 1,285,720 | 100%       |
 * | device_knx_address       | 1,285,720 | 100%       |
 * | device_multicast_address | 1,285,720 | 100%       |
 * | device_mac_vendor        | 1,238,458 | 96.32%     |
 * +--------------------------+-----------+------------+
 */
class KnxParser extends AbstractJsonDataParser
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
