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
        return [
            new ParsedDeviceData(
                vendor: 'not_parsed',
            ),
        ];
    }
}
