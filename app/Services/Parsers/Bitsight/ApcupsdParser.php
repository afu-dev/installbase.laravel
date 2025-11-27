<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

class ApcupsdParser extends AbstractJsonDataParser
{
    /** @return ParsedDeviceData[] */
    protected function parseData(): array
    {
        $fingerprintValue = null;
        $fingerprints = $this->extractJson(["Fingerprint", "fingerprint"]);
        if (!empty($fingerprints)) {
            foreach ($fingerprints as $fingerprint) {
                if (!empty($fingerprint["fingerprint"])) {
                    $fingerprintValue = str_starts_with($fingerprint["fingerprint"], "model/")
                        ? str_replace("model/", "", $fingerprint["fingerprint"])
                        : $fingerprint["fingerprint"];
                    break;
                }
            }
        } else {
            $fingerprintValue = $this->extract(["Fingerprint", "fingerprint"]);
        }

        return [
            new ParsedDeviceData(
                vendor: $this->extractNested(["Apcupsd", "apcupsd"], ["vendor", "Vendor"]) ?? $this->extract(["Vendor", "vendor", "vendor_name"]) ?? "unknown",
                fingerprint: $this->extractNested(["Apcupsd", "apcupsd"], ["model", "Model"]),
                version: $this->extractNested(["Apcupsd", "apcupsd"], ["version", "Version"]),
                sn: $this->extractNested(["Apcupsd", "apcupsd"], ["serialno", "Serialno"]),
                fingerprint_raw: $fingerprintValue,
            ),
        ];
    }
}
