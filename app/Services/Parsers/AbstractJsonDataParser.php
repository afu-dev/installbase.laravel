<?php

namespace App\Services\Parsers;

use App\Contracts\DataParserInterface;

abstract class AbstractJsonDataParser implements DataParserInterface
{
    protected array $jsonData;

    public function parse(string $rawData): ParsedDeviceData
    {
        $this->jsonData = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON data: ' . json_last_error_msg());
        }

        return $this->parseData();
    }

    abstract protected function parseData(): ParsedDeviceData;

    protected function extract(string|array $keys, mixed $default = null): mixed
    {
        // Convert single key to array for uniform handling
        $keysArray = is_array($keys) ? $keys : [$keys];

        foreach ($keysArray as $key) {
            $value = data_get($this->jsonData, $key);

            // Return first non-null value found
            if ($value !== null) {
                return $value;
            }
        }

        return $default;
    }

    protected function extractInt(string $key, int $default = 0): int
    {
        return (int)$this->extract($key, $default);
    }

    protected function extractArray(string $key, array $default = []): array
    {
        $value = $this->extract($key);

        return is_array($value) ? $value : $default;
    }

    protected function extractBool(string $key, bool $default = false): bool
    {
        return (bool)$this->extract($key, $default);
    }

    protected function extractJson(string $key, array $default = []): array
    {
        $value = $this->extract($key);

        return json_validate($value) ? json_decode($value, true) : $default;
    }
}
