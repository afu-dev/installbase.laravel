<?php

namespace Tests\Unit\Services\Parsers;

use App\Services\Parsers\ParsedDeviceData;
use PHPUnit\Framework\TestCase;

class ParserTestCase extends TestCase
{
    /**
     * @param ParsedDeviceData[] $resultArray
     */
    protected function assertAllDevices(array $resultArray): void
    {
        foreach ($resultArray as $device) {
            $this->assertInstanceOf(ParsedDeviceData::class, $device);
        }
    }
}
