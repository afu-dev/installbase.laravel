<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

class BacnetParser extends AbstractRawDataParser
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
