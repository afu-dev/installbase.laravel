<?php

namespace Tests\Unit\Services\Parsers\Shodan;

use App\Services\Parsers\Shodan\Iec61850Parser;
use PHPUnit\Framework\TestCase;

class Iec61850ParserTest extends TestCase
{
    public function test_it_parses_shodan_iec61850_data_1(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/shodan_iec-61850_1.txt");

        $result = $parser->parse($data);

        $this->assertEquals('not_parsed', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertNull($result->version);
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

    public function test_it_parses_shodan_iec61850_data_2(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/shodan_iec-61850_2.txt");

        $result = $parser->parse($data);

        $this->assertEquals('not_parsed', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertNull($result->version);
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

    public function test_it_parses_shodan_iec61850_data_3(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/shodan_iec-61850_3.txt");

        $result = $parser->parse($data);

        $this->assertEquals('not_parsed', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertNull($result->version);
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

    public function test_it_parses_shodan_iec61850_data_4(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/shodan_iec-61850_4.txt");

        $result = $parser->parse($data);

        $this->assertEquals('not_parsed', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertNull($result->version);
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

    public function test_it_parses_shodan_iec61850_data_5(): void
    {
        $parser = new Iec61850Parser();

        $data = file_get_contents("tests/fixtures/parsers/iec-61850/shodan_iec-61850_5.txt");

        $result = $parser->parse($data);

        $this->assertEquals('not_parsed', $result->vendor);
        $this->assertNull($result->fingerprint);
        $this->assertNull($result->version);
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
