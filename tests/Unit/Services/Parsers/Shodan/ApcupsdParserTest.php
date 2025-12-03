<?php

namespace Tests\Unit\Services\Parsers\Shodan;

use App\Services\Parsers\Shodan\ApcupsdParser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class ApcupsdParserTest extends ParserTestCase
{
    public function test_it_parses_shodan_apcupsd_data_1(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/shodan_apcupsd_1.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertEquals('Smart-UPS RT 5000 XL', $device->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) redhat', $device->version);
        $this->assertEquals('IS1042007402', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_apcupsd_data_2(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/shodan_apcupsd_2.txt");

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

    public function test_it_parses_shodan_apcupsd_data_3(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/shodan_apcupsd_3.txt");

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

    public function test_it_parses_shodan_apcupsd_data_4(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/shodan_apcupsd_4.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertEquals('Smart-UPS_1500', $device->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) freebsd', $device->version);
        $this->assertEquals('AS2017150708', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_apcupsd_data_5(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/shodan_apcupsd_5.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('unknown', $device->vendor);
        $this->assertEquals('Back-UPS RS 1500G', $device->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) mingw', $device->version);
        $this->assertEquals('4B1822P37733', $device->sn);
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
