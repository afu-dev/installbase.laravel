<?php

namespace App\Contracts;

use App\Services\Parsers\ParsedDeviceData;

interface DataParserInterface
{
    /** @return ParsedDeviceData[] */
    public function parse(string $rawData): array;
}
