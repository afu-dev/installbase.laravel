<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts device information from the SNMP protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * Snmp Key Frequency:
 * +--------------+--------+------------+
 * | Key          | Count  | Percentage |
 * +--------------+--------+------------+
 * | snmp_data    | 65,476 | 94.62%     |
 * | hash_data    | 51,270 | 74.09%     |
 * | product_name | 34,659 | 50.08%     |
 * +--------------+--------+------------+
 */
class SnmpParser extends AbstractJsonDataParser
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
