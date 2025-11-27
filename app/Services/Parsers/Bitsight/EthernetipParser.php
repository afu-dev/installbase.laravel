<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * Should only return one device per detected.
 *
 * ---
 *
 * Ethernetip Key Frequency:
 * +----------------------+-----------+------------+
 * | Key                  | Count     | Percentage |
 * +----------------------+-----------+------------+
 * | command              | 1,020,791 | 100%       |
 * | command_length       | 1,020,791 | 100%       |
 * | command_status       | 1,020,791 | 100%       |
 * | device_type          | 1,020,791 | 100%       |
 * | encapsulation_length | 1,020,791 | 100%       |
 * | ip                   | 1,020,791 | 100%       |
 * | item_count           | 1,020,791 | 100%       |
 * | product_code         | 1,020,791 | 100%       |
 * | product_name         | 1,020,791 | 100%       |
 * | product_name_length  | 1,020,791 | 100%       |
 * | revision_major       | 1,020,791 | 100%       |
 * | revision_minor       | 1,020,791 | 100%       |
 * | sender_context       | 1,020,791 | 100%       |
 * | serial               | 1,020,791 | 100%       |
 * | socket_addr          | 1,020,791 | 100%       |
 * | state                | 1,020,791 | 100%       |
 * | type_id              | 1,020,791 | 100%       |
 * | vendor_id            | 1,020,791 | 100%       |
 * | version              | 1,020,791 | 100%       |
 * | options              | 1,020,790 | 100%       |
 * | session              | 1,020,790 | 100%       |
 * | status               | 1,020,683 | 99.99%     |
 * +----------------------+-----------+------------+
 */
class EthernetipParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        return [
            new ParsedDeviceData(
                vendor: $this->extractNested(["ethernetip", "Ethernetip"], "vendor_id"),
                fingerprint: $this->extractNested(["ethernetip", "Ethernetip"], "product_name"),
                version: $this->extractNested(["ethernetip", "Ethernetip"], "revision_major") . "." . $this->extractNested(["ethernetip", "Ethernetip"], "revision_minor"),
            ),
        ];
    }
}
