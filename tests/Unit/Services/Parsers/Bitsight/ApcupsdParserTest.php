<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\ApcupsdParser;
use Tests\Unit\Services\Parsers\ParserTestCase;

class ApcupsdParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_apcupsd_data_1(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertNull($device->fingerprint);
        $this->assertEquals("3.14.14 (31 May 2016) freebsd", $device->version);
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

    public function test_it_parses_bitsight_apcupsd_data_2(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertNull($device->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) redhat', $device->version);
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

    public function test_it_parses_bitsight_apcupsd_data_3(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertNull($device->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) freebsd', $device->version);
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

    public function test_it_parses_bitsight_apcupsd_data_4(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertEquals(b"Smart-UPS X\x013000", $device->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) freebsd', $device->version);
        $this->assertEquals('AQ1435235839', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_apcupsd_data_5(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertNull($device->fingerprint);
        $this->assertEquals('3.14.12 (29 March 2014) redhat', $device->version);
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

    public function test_it_parses_bitsight_apcupsd_data_6(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_6.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('schneider_electric', $device->vendor);
        $this->assertEquals('Back-UPS BK650M2-CH', $device->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) slackware', $device->version);
        $this->assertEquals('000000000000', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertEquals('Back-UPS BK650M2-CH', $device->fingerprint_raw);
    }
}
