<?php

namespace App\Services\Parsers\Censys;

use App\Services\Parsers\AbstractJsonDataParser;

class FtpParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        // TODO: Awaiting Censys FTP data structure clarification
        // For now, delegate all cases to OtherParser
        $otherParser = new OtherParser();
        return $otherParser->parse($this->rawData);
    }
}
