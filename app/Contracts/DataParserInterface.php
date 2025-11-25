<?php

namespace App\Contracts;

use App\Services\Parsers\ParsedDeviceData;

interface DataParserInterface
{
    public function parse(string $rawData): ParsedDeviceData;
}
