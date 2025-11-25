<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

class Iec61850Parser extends AbstractRawDataParser
{
    protected function parseData(): ParsedDeviceData
    {
        return new ParsedDeviceData(
            vendor: 'not_parsed',
        );
    }
}
