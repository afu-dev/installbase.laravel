<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\KnxParser;
use Tests\Unit\Services\Parsers\ParserTestCase;

class KnxParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_knx_data_1(): void
    {
        $parser = new KnxParser();

        $data = file_get_contents("tests/fixtures/parsers/knx/bitsight_knx_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Siemens AG', $device->vendor);
        $this->assertEquals('IP_KNX_Interface', $device->fingerprint);
        $this->assertEquals('1.1.4', $device->version);
        $this->assertEquals('0001004c7f02', $device->sn);
        $this->assertEquals('00:0E:8C:01:93:93', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_knx_data_2(): void
    {
        $parser = new KnxParser();

        $data = file_get_contents("tests/fixtures/parsers/knx/bitsight_knx_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('F & S Elektronik Systeme GmbH', $device->vendor);
        $this->assertEquals('Servidor Visu NFF', $device->fingerprint);
        $this->assertEquals('1.1.6', $device->version);
        $this->assertEquals('00ef510b9476', $device->sn);
        $this->assertEquals('00:05:51:0B:94:76', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_knx_data_3(): void
    {
        $parser = new KnxParser();

        $data = file_get_contents("tests/fixtures/parsers/knx/bitsight_knx_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Loxone Electronics GmbH', $device->vendor);
        $this->assertEquals('Rapp Fabrice', $device->fingerprint);
        $this->assertEquals('1.1.250', $device->version);
        $this->assertEquals('504f94112a90', $device->sn);
        $this->assertEquals('50:4F:94:11:2A:90', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_knx_data_4(): void
    {
        $parser = new KnxParser();

        $data = file_get_contents("tests/fixtures/parsers/knx/bitsight_knx_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('ABB STOTZ-KONTAKT GmbH', $device->vendor);
        $this->assertEquals('ABB IP-Router IPR/S', $device->fingerprint);
        $this->assertEquals('1.0.200', $device->version);
        $this->assertEquals('0002e8c0ff50', $device->sn);
        $this->assertEquals('00:0C:DE:C0:50:E8', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_knx_data_5(): void
    {
        $parser = new KnxParser();

        $data = file_get_contents("tests/fixtures/parsers/knx/bitsight_knx_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Loxone Electronics GmbH', $device->vendor);
        $this->assertEquals('Govaere Bouw', $device->fingerprint);
        $this->assertEquals('1.1.250', $device->version);
        $this->assertEquals('504f94117d33', $device->sn);
        $this->assertEquals('50:4F:94:11:7D:33', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

}
