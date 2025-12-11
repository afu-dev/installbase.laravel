<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts APC UPS device information from the Apcupsd protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * Apcupsd Key Frequency:
 * +-----------+--------+------------+
 * | Key       | Count  | Percentage |
 * +-----------+--------+------------+
 * | apc       | 25,724 | 100%       |
 * | cable     | 25,724 | 100%       |
 * | date      | 25,724 | 100%       |
 * | hostname  | 25,724 | 100%       |
 * | upsmode   | 25,724 | 100%       |
 * | version   | 25,724 | 100%       |
 * | status    | 25,723 | 100%       |
 * | end_apc   | 25,613 | 99.57%     |
 * | driver    | 24,336 | 94.6%      |
 * | upsname   | 21,690 | 84.32%     |
 * | model     | 18,293 | 71.11%     |
 * | serialno  | 18,159 | 70.59%     |
 * | firmware  | 17,319 | 67.33%     |
 * | master    | 6,731  | 26.17%     |
 * | starttime | 6,575  | 25.56%     |
 * | masterupd | 1,761  | 6.85%      |
 * | apcmodel  | 1,022  | 3.97%      |
 * | release   | 482    | 1.87%      |
 * +-----------+--------+------------+
 */
class ApcupsdParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        $fingerprintValue = null;
        $fingerprints = $this->extractJson(["Fingerprint", "fingerprint"]);
        if (!empty($fingerprints)) {
            foreach ($fingerprints as $fingerprint) {
                if (!empty($fingerprint["fingerprint"])) {
                    $fingerprintValue = str_starts_with((string)$fingerprint["fingerprint"], "model/")
                        ? str_replace("model/", "", $fingerprint["fingerprint"])
                        : $fingerprint["fingerprint"];
                    break;
                }
            }
        } else {
            $fingerprintValue = $this->extract(["Fingerprint", "fingerprint"]);
        }

        $brandVendor = null;
        foreach ($this->extractJson(["Apcupsd", "apcupsd"]) as $key => $value) {
            if (!in_array($key, ["vendor", "Vendor"])) { // can also match for model/Model
                continue;
            }

            $brandVendor = $this->detectBrand($value);
            if ($brandVendor !== null) {
                break;
            }
        }

        $vendor = $brandVendor
            ?? $this->extract(["Vendor", "vendor", "vendor_name"])
            ?? "unknown";

        return [
            new ParsedDeviceData(
                vendor: $this->detectBrand($vendor) ?? $vendor,
                fingerprint: $this->extractNested(["Apcupsd", "apcupsd"], ["model", "Model"]),
                version: $this->extractNested(["Apcupsd", "apcupsd"], ["version", "Version"]),
                sn: $this->extractNested(["Apcupsd", "apcupsd"], ["serialno", "Serialno"]),
                fingerprint_raw: $fingerprintValue,
            ),
        ];
    }
}
