<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\EthernetipParser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class EthernetipParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_ethernetip_data_1(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Rockwell Automation/Allen-Bradley', $device->vendor);
        $this->assertEquals('1763-L16DWD B/16.00', $device->fingerprint);
        $this->assertEquals('2.16', $device->version);
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

    public function test_it_parses_bitsight_ethernetip_data_2(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Rockwell Automation/Allen-Bradley', $device->vendor);
        $this->assertEquals('1769-L19ER-BB1B/A LOGIX5319ER', $device->fingerprint);
        $this->assertEquals('30.11', $device->version);
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

    public function test_it_parses_bitsight_ethernetip_data_3(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Rockwell Automation/Allen-Bradley', $device->vendor);
        $this->assertEquals('1769-L30ER/A LOGIX5330ER', $device->fingerprint);
        $this->assertEquals('30.11', $device->version);
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

    public function test_it_parses_bitsight_ethernetip_data_4(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Rockwell Automation/Allen-Bradley', $device->vendor);
        $this->assertEquals('2080-LC20-20QBB', $device->fingerprint);
        $this->assertEquals('10.12', $device->version);
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

    public function test_it_parses_bitsight_ethernetip_data_5(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Omron Corporation', $device->vendor);
        $this->assertEquals('NX1P2', $device->fingerprint);
        $this->assertEquals('2.9', $device->version);
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
