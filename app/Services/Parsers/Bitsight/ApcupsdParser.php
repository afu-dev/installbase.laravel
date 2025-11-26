<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class ApcupsdParser extends AbstractJsonDataParser
{
    /** @return ParsedDeviceData[] */
    protected function parseData(): array
    {
        return [
            new ParsedDeviceData(
                vendor: $this->extractNested(["Apcupsd", "apcupsd"], ["vendor", "Vendor"]) ?? $this->extract(["Vendor", "vendor", "vendor_name"]) ?? "unknown",
                fingerprint: $this->extractNested(["Apcupsd", "apcupsd"], ["model", "Model"]),
                version: $this->extractNested(["Apcupsd", "apcupsd"], ["version", "Version"]),
                sn: $this->extractNested(["Apcupsd", "apcupsd"], ["serialno", "Serialno"]),
                fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"]),
            ),
        ];
    }
}
