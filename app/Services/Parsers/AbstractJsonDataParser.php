<?php

namespace App\Services\Parsers;

use App\Contracts\DataParserInterface;
use App\Models\Brand;
use Error;
use Exception;
use InvalidArgumentException;

abstract class AbstractJsonDataParser implements DataParserInterface
{
    protected array $jsonData;

    protected string $rawData;

    private array $nestedCache = [];

    protected static ?array $brands = null;

    /** @return ParsedDeviceData[] */
    public function parse(string $rawData): array
    {
        $this->rawData = $rawData;
        $this->jsonData = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON data: ' . json_last_error_msg());
        }

        return $this->parseData();
    }

    /** @return ParsedDeviceData[] */
    abstract protected function parseData(): array;

    protected function extract(string|array $keys, mixed $default = null): mixed
    {
        // Convert single key to array for uniform handling
        $keysArray = is_array($keys) ? $keys : [$keys];

        foreach ($keysArray as $key) {
            $value = data_get($this->jsonData, $key);

            // Return first non-empty value found
            if (!empty($value)) {
                return $value;
            }
        }

        return $default;
    }

    protected function extractInt(string $key, int $default = 0): int
    {
        return (int)$this->extract($key, $default);
    }

    protected function extractArray(string|array $keys, array $default = []): array
    {
        $value = $this->extract($keys);

        return is_array($value) ? $value : $default;
    }

    protected function extractBool(string $key, bool $default = false): bool
    {
        return (bool)$this->extract($key, $default);
    }

    protected function extractJson(string|array $keys, array $default = []): array
    {
        $value = $this->extract($keys);

        return $value !== null && json_validate($value) ? json_decode($value, true) : $default;
    }

    protected function extractNested(string|array $parentKeys, string|array $childKeys, mixed $default = null): mixed
    {
        $cacheKey = is_array($parentKeys) ? implode('|', $parentKeys) : $parentKeys;

        if (!isset($this->nestedCache[$cacheKey])) {
            $jsonString = $this->extract($parentKeys);
            if (empty($jsonString) || !json_validate($jsonString)) {
                $this->nestedCache[$cacheKey] = $default;
            } else {
                $this->nestedCache[$cacheKey] = json_decode($jsonString, true);
            }
        }

        $parsedData = $this->nestedCache[$cacheKey];
        if ($parsedData === null) {
            return $default;
        }

        $childKeysArray = is_array($childKeys) ? $childKeys : [$childKeys];

        foreach ($childKeysArray as $key) {
            $value = data_get($parsedData, $key);
            if (!empty($value)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Lazy-load brands once per request lifecycle.
     * Shared across all parser instances for performance.
     * Returns empty array if database not available (e.g., unit tests).
     */
    protected function getBrands(): array
    {
        if (self::$brands === null) {
            try {
                self::$brands = Brand::pluck('brand')->all();
            } catch (Exception|Error) {
                // Database not available (unit tests, etc.) - return static array
                self::$brands = ["apc", "areva", "etap", "invensys", "merlin", "pro face", "pro-face", "proface", "schneider", "square d", "square-d", "TAC", "telemecanique", "vamp", "veris", "wiser",];
            }
        }

        return self::$brands;
    }

    /**
     * Detect if raw data contains any known brand (case-insensitive).
     * Returns "Schneider Electric" if match found, null otherwise.
     */
    protected function detectBrand(?string $rawData): ?string
    {
        if ($rawData === null) {
            return null;
        }

        if (array_any($this->getBrands(), fn ($brand) => str_contains(strtolower($rawData), strtolower((string) $brand)))) {
            return "Schneider Electric";
        }

        return null;
    }
}
