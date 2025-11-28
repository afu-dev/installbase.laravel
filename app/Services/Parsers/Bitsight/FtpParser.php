<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

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
