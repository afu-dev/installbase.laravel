<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\ModbusParser;
use Tests\Unit\Services\Parsers\ParserTestCase;

class ModbusParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_modbus_data_1(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);

        // Should return 3 devices (uid 0, 1, 255)
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayHasKey(255, $result);

        // Check first device (uid 0)
        $device = $result[0];
        $this->assertEquals("Schneider Electric", $device->vendor);
        $this->assertEquals("TWDLCAE40DRF", $device->fingerprint); // From root Fingerprint JSON
        $this->assertEquals("05.40", $device->version);
        $this->assertNull($device->modbus_project_info);

        // All devices should have same data since they have identical device_identification
        $this->assertEquals($device->vendor, $result[1]->vendor);
        $this->assertEquals($device->fingerprint, $result[1]->fingerprint);
        $this->assertEquals($device->version, $result[1]->version);
    }

    public function test_it_parses_bitsight_modbus_data_2(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);

        // All devices have errors - return single fallback device
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);

        $device = $result[0];
        $this->assertEquals("unknown", $device->vendor); // Empty Vendor field in fixture
        $this->assertNull($device->fingerprint); // Empty Fingerprint field
        $this->assertNull($device->version);
        $this->assertNull($device->modbus_project_info);
    }

    public function test_it_parses_bitsight_modbus_data_3(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);

        // All devices have "Illegal Function (Error)" - return single fallback device
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);

        $device = $result[0];
        $this->assertEquals("unknown", $device->vendor); // Empty Vendor field in fixture
        $this->assertNull($device->fingerprint); // Empty Fingerprint field
        $this->assertNull($device->version);
        $this->assertNull($device->modbus_project_info);
    }

    public function test_it_parses_bitsight_modbus_data_4(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);

        // Should return 3 devices (uid 0, 1, 255)
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayHasKey(255, $result);

        // Check first device (uid 0)
        $device = $result[0];
        $this->assertEquals("Schneider Electric", $device->vendor);
        $this->assertEquals("TM251MESE", $device->fingerprint); // From root Fingerprint JSON
        $this->assertEquals("04.00.06.38", $device->version);
        $this->assertNull($device->modbus_project_info);
    }

    public function test_it_parses_bitsight_modbus_data_5(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);

        // Device has error - return single fallback device
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);

        $device = $result[0];
        $this->assertEquals("unknown", $device->vendor); // Empty Vendor field in fixture
        $this->assertNull($device->fingerprint); // Empty Fingerprint field
        $this->assertNull($device->version);
        $this->assertNull($device->modbus_project_info);
    }

    public function test_it_parses_bitsight_modbus_data_6(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_6.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);

        // Should return 3 devices (uid 0, 1, 255)
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayHasKey(255, $result);

        // Check first device (uid 0) - has project_information
        $device = $result[0];
        $this->assertEquals("Schneider Electric", $device->vendor);
        $this->assertEquals("BMX P34 2020", $device->fingerprint); // From root Fingerprint JSON
        $this->assertEquals("2.4", $device->version);
        $this->assertEquals("EDAR HOZ DE JACA - E JACA         V5.0", $device->modbus_project_info);

        // Check device uid 1 - no project_information
        $device1 = $result[1];
        $this->assertEquals("Schneider Electric", $device1->vendor);
        $this->assertEquals("BMX P34 2020", $device1->fingerprint);
        $this->assertEquals("2.4", $device1->version);
        $this->assertNull($device1->modbus_project_info);

        // Check device uid 255 - no project_information
        $device255 = $result[255];
        $this->assertEquals("Schneider Electric", $device255->vendor);
        $this->assertEquals("BMX P34 2020", $device255->fingerprint);
        $this->assertEquals("2.4", $device255->version);
        $this->assertNull($device255->modbus_project_info);
    }

    public function test_it_parses_bitsight_modbus_data_7(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/bitsight_modbus_7.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);

        // Should return 3 devices (uid 0, 1, 255)
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayHasKey(255, $result);

        // Check device uid 0
        // device_identification: "HUAWEI Smart Logger V300R001C00SPC110"
        // Empty root Vendor field, empty Fingerprint, no cpu_module
        $device0 = $result[0];
        $this->assertEquals("HUAWEI", $device0->vendor);
        $this->assertEquals("Smart Logger", $device0->fingerprint);
        $this->assertEquals("300R001C00SPC110", $device0->version);
        $this->assertNull($device0->modbus_project_info);

        // Check device uid 1
        $device1 = $result[1];
        $this->assertEquals("HUAWEI", $device1->vendor);
        $this->assertEquals("Smart Logger", $device1->fingerprint);
        $this->assertEquals("300R001C00SPC110", $device1->version);
        $this->assertNull($device1->modbus_project_info);

        // Check device uid 255
        $device255 = $result[255];
        $this->assertEquals("HUAWEI", $device255->vendor);
        $this->assertEquals("Smart Logger", $device255->fingerprint);
        $this->assertEquals("300R001C00SPC110", $device255->version);
        $this->assertNull($device255->modbus_project_info);
    }
}
