<?php

namespace Tests\Unit\Services\Parsers\Shodan;

use App\Services\Parsers\Shodan\EthernetipParser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class EthernetipParserTest extends ParserTestCase
{
    public function test_it_parses_shodan_ethernetip_data_1(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/shodan_ethernetip_1.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('PM5560', $device->fingerprint);
        $this->assertNull($device->version);
        $this->assertEquals('0x23c7193a', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_ethernetip_data_2(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/shodan_ethernetip_2.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('PM5560', $device->fingerprint);
        $this->assertNull($device->version);
        $this->assertEquals('0x23c6d120', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_ethernetip_data_3(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/shodan_ethernetip_3.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('PM5560', $device->fingerprint);
        $this->assertNull($device->version);
        $this->assertEquals('0x23c5453d', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_ethernetip_data_4(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/shodan_ethernetip_4.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('PM5560', $device->fingerprint);
        $this->assertNull($device->version);
        $this->assertEquals('0x23c54a57', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_ethernetip_data_5(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/shodan_ethernetip_5.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('PM5560', $device->fingerprint);
        $this->assertNull($device->version);
        $this->assertEquals('0x23c54a75', $device->sn);
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
