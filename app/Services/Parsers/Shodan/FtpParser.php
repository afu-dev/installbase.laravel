<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

class FtpParser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        // Check for APXXXX pattern in raw FTP banner
        if (preg_match('/AP\d{4}/', $this->rawData, $matches)) {
            $model = $matches[0]; // e.g., "AP9630"

            // Extract version (pattern: v6.4.6)
            $version = null;
            if (preg_match('/v\d+\.\d+\.\d+/', $this->rawData, $versionMatches)) {
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
