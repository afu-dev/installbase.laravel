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

        $this->assertEquals('not_parsed', $device->vendor);
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

    public function test_it_parses_bitsight_ethernetip_data_2(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('not_parsed', $device->vendor);
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

    public function test_it_parses_bitsight_ethernetip_data_3(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('not_parsed', $device->vendor);
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

    public function test_it_parses_bitsight_ethernetip_data_4(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('not_parsed', $device->vendor);
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

    public function test_it_parses_bitsight_ethernetip_data_5(): void
    {
        $parser = new EthernetipParser();

        $data = file_get_contents("tests/fixtures/parsers/ethernetip/bitsight_ethernetip_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('not_parsed', $device->vendor);
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
