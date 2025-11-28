<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * Should only return one device per detection.
 *
 * ---
 *
 * This parser checks for Schneider Electric APC devices by detecting the APXXXX pattern
 * (where XXXX = exactly 4 digits, e.g., AP7900, AP9630) in the ftp_data field.
 *
 * If the APXXXX pattern is found:
 * - Extracts model number (e.g., AP7900)
 * - Extracts version if available (e.g., v3.7.0)
 * - Returns ParsedDeviceData with vendor='Schneider Electric'
 *
 * If the APXXXX pattern is NOT found:
 * - Delegates to OtherParser which returns vendor='not_parsed'
 *
 * ---
 *
 * Ftp Key Frequency:
 * +--------------+---------+------------+
 * | Key          | Count   | Percentage |
 * +--------------+---------+------------+
 * | ftp_data     | 129,056 | 93.34%     |
 * | hash_data    | 80,290  | 58.07%     |
 * | product_name | 16,449  | 11.9%      |
 * +--------------+---------+------------+
 */
class FtpParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        // Extract nested FTP data (handles both "Ftp" and "ftp" keys)
        $ftpData = $this->extractNested(["Ftp", "ftp"], "ftp_data");

        // Check for APXXXX pattern (AP + exactly 4 digits)
        if (!empty($ftpData) && preg_match('/AP\d{4}/', $ftpData, $matches)) {
            $model = $matches[0]; // e.g., "AP7900"

            // Extract version (pattern: v3.7.0, v6.4.6, etc.)
            $version = null;
            if (preg_match('/v\d+\.\d+\.\d+/', $ftpData, $versionMatches)) {
                $version = $versionMatches[0];
            }

            return [
                new ParsedDeviceData(
                    vendor: 'Schneider Electric',
                    fingerprint: $model,
                    version: $version,
                    nmc_card_num: $model,
                ),
            ];
        }

        // Delegate to OtherParser if pattern not found
        $otherParser = new OtherParser();
        return $otherParser->parse($this->rawData);
    }
}
