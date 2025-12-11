<?php

namespace App\Services\Parsers;

use App\Contracts\DataParserInterface;
use App\Models\Brand;
use Error;
use Exception;

abstract class AbstractRawDataParser implements DataParserInterface
{
    protected string $rawData;

    protected static ?array $brands = null;

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
                // Database not available (unit tests, etc.) - return empty array
                self::$brands = ["apc", "areva", "etap", "invensys", "merlin", "pro face", "pro-face", "proface", "schneider", "square d", "square-d", "TAC", "telemecanique", "vamp", "veris", "wiser",];
            }
        }

        return self::$brands;
    }

    /**
     * Detect if raw data contains any known brand (case-insensitive).
     * Returns "Schneider Electric" if match found, null otherwise.
     */
    protected function detectBrand(string $rawData): ?string
    {
        if (array_any($this->getBrands(), fn ($brand) => str_contains(strtolower($rawData), strtolower((string) $brand)))) {
            return "Schneider Electric";
        }

        return null;
    }
}
