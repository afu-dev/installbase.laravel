<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

class FtpParser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        preg_match('/ (AP(\d{4})[A-Z]{0,2}) /', $this->rawData, $matches);
        if (empty($matches[2])) {
            $otherParser = new OtherParser();
            return $otherParser->parse($this->rawData);
        }

        [, $fingerprint, $cardNum] = $matches;

        return [
            new ParsedDeviceData(
                vendor: "Schneider Electric",
                fingerprint: $fingerprint,
                version: $this->extract("/ (v\d+(?:\.\d+)+)/"),
                nmc_card_num: $cardNum,
            )
        ];
    }
}
