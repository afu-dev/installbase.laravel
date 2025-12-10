<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class OtherParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        // Brand detection first, then fallback to existing vendor extraction
        $vendor = $this->extract(["Vendor", "vendor"]) ?? "unknown";

        return [
            new ParsedDeviceData(
                vendor: $this->detectBrand($vendor) ?? $vendor,
            ),
        ];
    }
}
