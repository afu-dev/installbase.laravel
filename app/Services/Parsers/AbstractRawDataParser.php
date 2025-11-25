<?php

namespace App\Services\Parsers;

use App\Contracts\DataParserInterface;

abstract class AbstractRawDataParser implements DataParserInterface
{
    protected string $rawData;

    public function parse(string $rawData): ParsedDeviceData
    {
        $this->rawData = $rawData;

        return $this->parseData();
    }

    abstract protected function parseData(): ParsedDeviceData;

    protected function extract(string $pattern, mixed $default = null): mixed
    {
        if (preg_match($pattern, $this->rawData, $matches)) {
            return $matches[1] ?? $default;
        }

        return $default;
    }
}
