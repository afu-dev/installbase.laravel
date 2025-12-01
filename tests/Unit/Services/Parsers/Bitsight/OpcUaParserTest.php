<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\OpcUaParser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class OpcUaParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_opcua_data_1(): void
    {
        $parser = new OpcUaParser();

        $data = file_get_contents("tests/fixtures/parsers/opc-ua/bitsight_opc-ua_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Beijer Electronics AB', $device->vendor);
        $this->assertEquals('iX Developer 2.20', $device->fingerprint);
        $this->assertEquals('2.20 SP1', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertEquals('http://opcfoundation.org/UA/SecurityPolicy#None', $device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_opcua_data_2(): void
    {
        $parser = new OpcUaParser();

        $data = file_get_contents("tests/fixtures/parsers/opc-ua/bitsight_opc-ua_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertNull($device->fingerprint);
        $this->assertNull($device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertEquals('http://opcfoundation.org/UA/SecurityPolicy#None', $device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_opcua_data_3(): void
    {
        $parser = new OpcUaParser();

        $data = file_get_contents("tests/fixtures/parsers/opc-ua/bitsight_opc-ua_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('M241-251 UA Server', $device->fingerprint);
        $this->assertEquals('V1.7.0 FOR INTERNAL USE ONLY - DO NOT DISTRIBUTE', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertEquals('http://opcfoundation.org/UA/SecurityPolicy#None', $device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_opcua_data_4(): void
    {
        $parser = new OpcUaParser();

        $data = file_get_contents("tests/fixtures/parsers/opc-ua/bitsight_opc-ua_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('WAGO', $device->vendor);
        $this->assertEquals('WAGO 750-8207 PFC200 2ETH RS 3G', $device->fingerprint);
        $this->assertEquals('3.5.14.30', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertEquals('http://opcfoundation.org/UA/SecurityPolicy#None', $device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_opcua_data_5(): void
    {
        $parser = new OpcUaParser();

        $data = file_get_contents("tests/fixtures/parsers/opc-ua/bitsight_opc-ua_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertNull($device->fingerprint);
        $this->assertNull($device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertEquals('http://opcfoundation.org/UA/SecurityPolicy#Basic128Rsa15', $device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

}
