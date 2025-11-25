<?php

namespace Tests\Unit\Services\Parsers\Censys;

use App\Services\Parsers\Censys\OtherParser;
use PHPUnit\Framework\TestCase;

class OtherParserTest extends TestCase
{
    public function test_it_parses_censys_other_data_1(): void
    {
        $parser = new OtherParser();

        $data = file_get_contents("tests/fixtures/parsers/other/censys_other_1.json");

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

    public function test_it_parses_censys_other_data_2(): void
    {
        $parser = new OtherParser();

        $data = file_get_contents("tests/fixtures/parsers/other/censys_other_2.json");

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

    public function test_it_parses_censys_other_data_3(): void
    {
        $parser = new OtherParser();

        $data = file_get_contents("tests/fixtures/parsers/other/censys_other_3.json");

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

    public function test_it_parses_censys_other_data_4(): void
    {
        $parser = new OtherParser();

        $data = file_get_contents("tests/fixtures/parsers/other/censys_other_4.json");

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

    public function test_it_parses_censys_other_data_5(): void
    {
        $parser = new OtherParser();

        $data = file_get_contents("tests/fixtures/parsers/other/censys_other_5.json");

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
