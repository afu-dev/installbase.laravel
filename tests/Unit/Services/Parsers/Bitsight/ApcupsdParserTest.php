<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\ApcupsdParser;
use PHPUnit\Framework\TestCase;

class ApcupsdParserTest extends TestCase
{
    public function test_it_parses_bitsight_apcupsd_data_1(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_1.json");

        $result = $parser->parse($data);

        $this->assertEquals('unknown', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertEquals("3.14.14 (31 May 2016) freebsd", $result->version);
        $this->assertNull($result->sn);
        $this->assertNull($result->device_mac);
        $this->assertNull($result->modbus_project_info);
        $this->assertNull($result->opc_ua_security_policy);
        $this->assertNull($result->is_guest_account_active);
        $this->assertNull($result->registration_info);
        $this->assertNull($result->secure_power_app);
        $this->assertNull($result->nmc_card_num);
        $this->assertNull($result->fingerprint_raw);
    }

    public function test_it_parses_bitsight_apcupsd_data_2(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_2.json");

        $result = $parser->parse($data);

        $this->assertEquals('unknown', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) redhat', $result->version);
        $this->assertNull($result->sn);
        $this->assertNull($result->device_mac);
        $this->assertNull($result->modbus_project_info);
        $this->assertNull($result->opc_ua_security_policy);
        $this->assertNull($result->is_guest_account_active);
        $this->assertNull($result->registration_info);
        $this->assertNull($result->secure_power_app);
        $this->assertNull($result->nmc_card_num);
        $this->assertNull($result->fingerprint_raw);
    }

    public function test_it_parses_bitsight_apcupsd_data_3(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_3.json");

        $result = $parser->parse($data);

        $this->assertEquals('unknown', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) freebsd', $result->version);
        $this->assertNull($result->sn);
        $this->assertNull($result->device_mac);
        $this->assertNull($result->modbus_project_info);
        $this->assertNull($result->opc_ua_security_policy);
        $this->assertNull($result->is_guest_account_active);
        $this->assertNull($result->registration_info);
        $this->assertNull($result->secure_power_app);
        $this->assertNull($result->nmc_card_num);
        $this->assertNull($result->fingerprint_raw);
    }

    public function test_it_parses_bitsight_apcupsd_data_4(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_4.json");

        $result = $parser->parse($data);

        $this->assertEquals('unknown', $result->vendor);
        $this->assertEquals(b"Smart-UPS X\x013000", $result->fingerprint);
        $this->assertEquals('3.14.14 (31 May 2016) freebsd', $result->version);
        $this->assertEquals('AQ1435235839', $result->sn);
        $this->assertNull($result->device_mac);
        $this->assertNull($result->modbus_project_info);
        $this->assertNull($result->opc_ua_security_policy);
        $this->assertNull($result->is_guest_account_active);
        $this->assertNull($result->registration_info);
        $this->assertNull($result->secure_power_app);
        $this->assertNull($result->nmc_card_num);
        $this->assertNull($result->fingerprint_raw);
    }

    public function test_it_parses_bitsight_apcupsd_data_5(): void
    {
        $parser = new ApcupsdParser();

        $data = file_get_contents("tests/fixtures/parsers/apcupsd/bitsight_apcupsd_5.json");

        $result = $parser->parse($data);

        $this->assertEquals('unknown', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertEquals('3.14.12 (29 March 2014) redhat', $result->version);
        $this->assertNull($result->sn);
        $this->assertNull($result->device_mac);
        $this->assertNull($result->modbus_project_info);
        $this->assertNull($result->opc_ua_security_policy);
        $this->assertNull($result->is_guest_account_active);
        $this->assertNull($result->registration_info);
        $this->assertNull($result->secure_power_app);
        $this->assertNull($result->nmc_card_num);
        $this->assertNull($result->fingerprint_raw);
    }

}
