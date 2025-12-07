<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\Iec61850Parser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class Iec61850ParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_iec61850_data_1(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/bitsight_iec-61850_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('S61850 for Easergy Relays', $device->fingerprint);
        $this->assertEquals('0.0.1', $device->version);
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

    public function test_it_parses_bitsight_iec61850_data_2(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/bitsight_iec-61850_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('S61850 for Easergy Relays', $device->fingerprint);
        $this->assertEquals('0.0.1', $device->version);
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

    public function test_it_parses_bitsight_iec61850_data_3(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/bitsight_iec-61850_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Unipower', $device->vendor);
        $this->assertEquals('UP2210', $device->fingerprint);
        $this->assertEquals('1.1.2', $device->version);
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

    public function test_it_parses_bitsight_iec61850_data_4(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/bitsight_iec-61850_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('iGrid T&D', $device->vendor);
        $this->assertEquals('iGComms', $device->fingerprint);
        $this->assertEquals('2.12.0', $device->version);
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

    public function test_it_parses_bitsight_iec61850_data_5(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/bitsight_iec-61850_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('VAMP Ltd.', $device->vendor);
        $this->assertEquals('S61850 for VAMP Relays', $device->fingerprint);
        $this->assertEquals('0.0.1', $device->version);
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

    public function test_it_parses_bitsight_iec61850_data_6(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/bitsight_iec-61850_6.json");

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
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

}
