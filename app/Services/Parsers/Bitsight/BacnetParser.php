<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class BacnetParser extends AbstractJsonDataParser
{
    protected function parseData(): ParsedDeviceData
    {
        return new ParsedDeviceData(
            vendor: 'not_parsed',
        );
    }
}
