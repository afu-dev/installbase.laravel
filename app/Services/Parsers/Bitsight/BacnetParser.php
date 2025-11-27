<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class BacnetParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        $vendor = $this->extractNested(["Bacnet", "bacnet"], ["Name", "name"]);
        if ($vendor === null && $this->extractNested(["Bacnet", "bacnet"], ["Error", "error"])) {
            $vendor = "Error";
        }

        return [
            new ParsedDeviceData(
                vendor: $vendor,
                fingerprint: $this->extractNested(["Bacnet", "bacnet"], ["Model", "model"]),
                version: $this->extractNested(["Bacnet", "bacnet"], ["Firmware", "firmware"]),
                fingerprint_raw: $this->extract(["Fingerprint", "fingerprint"])
            ),
        ];
    }
}
