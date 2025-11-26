<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\SnmpParser;
use PHPUnit\Framework\TestCase;

class SnmpParserTest extends TestCase
{
    public function test_it_parses_bitsight_snmp_data_1(): void
    {
        // Parser comme other
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_1.json");

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

    public function test_it_parses_bitsight_snmp_data_2(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_2.json");

        $result = $parser->parse($data);

        $this->assertEquals('not_parsed', $result->vendor);
        $this->assertEquals("AP7920", $result->fingerprint); // value: MN
        $this->assertNull($result->version);
        $this->assertNull($result->sn);
        $this->assertNull($result->device_mac);
        $this->assertNull($result->modbus_project_info);
        $this->assertNull($result->opc_ua_security_policy);
        $this->assertNull($result->is_guest_account_active);
        $this->assertNull($result->registration_info);
        $this->assertEquals("rpdu", $result->secure_power_app); // value: AN1
        $this->assertEquals("AP7920", $result->nmc_card_num); // value: MN
        $this->assertNull($result->fingerprint_raw);
    }

    public function test_it_parses_bitsight_snmp_data_3(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_3.json");

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

    public function test_it_parses_bitsight_snmp_data_4(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_4.json");

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

    public function test_it_parses_bitsight_snmp_data_5(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/bitsight_snmp_5.json");

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
