<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class Iec104Parser extends AbstractJsonDataParser
{
    protected function parseData(): ParsedDeviceData
    {
        return new ParsedDeviceData(
            vendor: 'not_parsed',
        );
    }
}
