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
     * Brands are stored in lowercase for efficient case-insensitive matching.
     */
    protected function getBrands(): array
    {
        if (self::$brands === null) {
            try {
                $brands = Brand::pluck('brand')->all();
                self::$brands = array_map(strtolower(...), $brands);
            } catch (Exception|Error) {
                // Database not available (unit tests, etc.) - return static array
                self::$brands = ["apc", "areva", "etap", "invensys", "merlin", "pro face", "pro-face", "proface", "schneider", "square d", "square-d", "tac", "telemecanique", "vamp", "veris", "wiser"];
            }
        }

        return self::$brands;
    }

    /**
     * Detect if raw data contains any known brand (case-insensitive).
     * Returns "Schneider Electric" if match found, null otherwise.
     * Optimized: converts raw data to lowercase once, brands are pre-computed as lowercase.
     */
    protected function detectBrand(string $rawData): ?string
    {
        $rawDataLower = strtolower($rawData);

        if (array_any($this->getBrands(), fn ($brand) => str_contains($rawDataLower, (string) $brand))) {
            return "Schneider Electric";
        }

        return null;
    }
}
