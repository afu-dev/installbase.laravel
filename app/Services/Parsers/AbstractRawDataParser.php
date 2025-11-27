<?php

namespace App\Services\Parsers;

use App\Contracts\DataParserInterface;

abstract class AbstractRawDataParser implements DataParserInterface
{
    protected string $rawData;

    /** @return ParsedDeviceData[] */
    public function parse(string $rawData): array
    {
        $this->rawData = $rawData;

        return $this->parseData();
    }

    /** @return ParsedDeviceData[] */
    abstract protected function parseData(): array;

    protected function extract(string $pattern, mixed $default = null): mixed
    {
        if (preg_match($pattern, $this->rawData, $matches)) {
            return $matches[1] ?? $default;
        }

        return $default;
    }
}
