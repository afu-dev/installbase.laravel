<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\BacnetParser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class BacnetParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_bacnet_data_1(): void
    {
        $parser = new BacnetParser();

        $data = file_get_contents("tests/fixtures/parsers/bacnet/bitsight_bacnet_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Tridium', $device->vendor);
        $this->assertEquals('Niagara4 Station', $device->fingerprint);
        $this->assertEquals('4.13.3.48', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_bacnet_data_2(): void
    {
        $parser = new BacnetParser();

        $data = file_get_contents("tests/fixtures/parsers/bacnet/bitsight_bacnet_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Siemens Building Technologies', $device->vendor);
        $this->assertEquals('Siemens BACnet-Server', $device->fingerprint);
        $this->assertEquals('1.2.0', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_bacnet_data_3(): void
    {
        $parser = new BacnetParser();

        $data = file_get_contents("tests/fixtures/parsers/bacnet/bitsight_bacnet_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Siemens Industry, Inc.', $device->vendor);
        $this->assertEquals('PXG3.L', $device->fingerprint);
        $this->assertEquals('FW=01.15.15.144;SVS-200:SBC=10.15;', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_bacnet_data_4(): void
    {
        $parser = new BacnetParser();

        $data = file_get_contents("tests/fixtures/parsers/bacnet/bitsight_bacnet_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Siemens Building Technologies', $device->vendor);
        $this->assertEquals('POS3.67', $device->fingerprint);
        $this->assertEquals('FW=03.39.03.38:BL=00.05.02.0003;SVS-300.4:SBC=13.24;', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_bacnet_data_5(): void
    {
        $parser = new BacnetParser();

        $data = file_get_contents("tests/fixtures/parsers/bacnet/bitsight_bacnet_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('bacnet_error', $device->vendor);
        $this->assertNull($device->fingerprint);
        $this->assertNull($device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

}
