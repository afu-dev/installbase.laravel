<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\SnmpParser;
use Tests\Unit\Services\Parsers\ParserTestCase;

class SnmpParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_snmp_data_1(): void
    {
        // Parser comme other
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
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

    public function test_it_parses_bitsight_snmp_data_2(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7920', $device->fingerprint);
        $this->assertEquals('v3.9.2', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('apc_hw02_rpdu_392.bin', $device->secure_power_app);
        $this->assertEquals('7920', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_snmp_data_3(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('NBRK0200', $device->fingerprint);
        $this->assertEquals('v3.7.5', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('apc_hw03_nb200_375.bin', $device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_snmp_data_4(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
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

    public function test_it_parses_bitsight_snmp_data_5(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7931', $device->fingerprint);
        $this->assertEquals('v3.7.4', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('apc_hw02_rpdu_374.bin', $device->secure_power_app);
        $this->assertEquals('7931', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

}
