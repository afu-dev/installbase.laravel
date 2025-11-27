<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class IonParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        return [
            new ParsedDeviceData(
                vendor: $this->extract(["Vendor", "vendor", "vendor_name"]),
                fingerprint: $this->extract("device_type"),
                version: $this->extract("revision"),
                sn: $this->extract("serial_num"),
                device_mac: $this->extract("mac_address"),
                fingerprint_raw: $this->extractArray("fingerprint"),
            ),
        ];
    }
}
