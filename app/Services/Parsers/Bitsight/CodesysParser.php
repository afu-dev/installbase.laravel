<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class CodesysParser extends AbstractJsonDataParser
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
