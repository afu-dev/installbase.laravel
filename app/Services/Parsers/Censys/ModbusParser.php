<?php

namespace App\Services\Parsers\Censys;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class ModbusParser extends AbstractJsonDataParser
{
    protected function parseData(): ParsedDeviceData
    {
        return new ParsedDeviceData(
            vendor: 'not_parsed',
        );
    }
}
