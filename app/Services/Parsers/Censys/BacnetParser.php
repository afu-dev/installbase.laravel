<?php

namespace App\Services\Parsers\Censys;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class BacnetParser extends AbstractJsonDataParser
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
