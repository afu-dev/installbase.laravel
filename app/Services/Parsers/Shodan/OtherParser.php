<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

class OtherParser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        // Brand detection first, then fallback to 'other_not_parsed'
        $vendor = $this->detectBrand($this->rawData);
        if ($vendor === null) {
            $vendor = 'unknown';
        }

        return [
            new ParsedDeviceData(
                vendor: $vendor,
            ),
        ];
    }
}
