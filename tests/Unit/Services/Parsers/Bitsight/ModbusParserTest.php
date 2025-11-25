<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\ModbusParser;
use PHPUnit\Framework\TestCase;

class ModbusParserTest extends TestCase
{
    public function test_it_parses_bitsight_modbus_data_1(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_1.json");

        $result = $parser->parse($data);

        // $this->assertEquals("Schneider Electric", $result->vendor);
    }

    public function test_it_parses_bitsight_modbus_data_2(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_2.json");

        $result = $parser->parse($data);

        // $this->assertEquals("Schneider Electric", $result->vendor);
    }

    public function test_it_parses_bitsight_modbus_data_3(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_3.json");

        $result = $parser->parse($data);

        // $this->assertEquals("Schneider Electric", $result->vendor);
    }

    public function test_it_parses_bitsight_modbus_data_4(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_4.json");

        $result = $parser->parse($data);

        // $this->assertEquals("Schneider Electric", $result->vendor);
    }

    public function test_it_parses_bitsight_modbus_data_5(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_5.json");

        $result = $parser->parse($data);

        // $this->assertEquals("Schneider Electric", $result->vendor);
    }
}
