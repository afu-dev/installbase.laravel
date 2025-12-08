<?php

namespace Tests\Unit\Services\Parsers\Shodan;

use App\Services\Parsers\Shodan\SnmpParser;
use Tests\Unit\Services\Parsers\ParserTestCase;

class SnmpParserTest extends ParserTestCase
{
    public function test_it_parses_shodan_snmpv2_data_1(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv2_1.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP8841', $device->fingerprint);
        $this->assertEquals('v5.1.4', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('rpdu2g', $device->secure_power_app);
        $this->assertEquals('AP8841', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv2_data_2(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv2_2.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP9640', $device->fingerprint);
        $this->assertEquals('v2.5.0.8', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('su', $device->secure_power_app);
        $this->assertEquals('AP9640', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv2_data_3(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv2_3.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP9630', $device->fingerprint);
        $this->assertEquals('v6.0.6', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('sumx', $device->secure_power_app);
        $this->assertEquals('AP9630', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv2_data_4(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv2_4.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP9631', $device->fingerprint);
        $this->assertEquals('v6.5.6', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('sumx', $device->secure_power_app);
        $this->assertEquals('AP9631', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv2_data_5(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv2_5.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP9631', $device->fingerprint);
        $this->assertEquals('v6.0.6', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('sumx', $device->secure_power_app);
        $this->assertEquals('AP9631', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv3_data_1(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv3_1.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7932', $device->fingerprint);
        $this->assertEquals('v3.5.7', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('rpdu', $device->secure_power_app);
        $this->assertEquals('AP7932', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv3_data_2(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv3_2.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7750A', $device->fingerprint);
        $this->assertEquals('v3.7.4', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('g2ats', $device->secure_power_app);
        $this->assertEquals('AP7750A', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv3_data_3(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv3_3.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP9538', $device->fingerprint);
        $this->assertEquals('v6.4.4', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('nb250', $device->secure_power_app);
        $this->assertEquals('AP9538', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv3_data_4(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv3_4.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP9630', $device->fingerprint);
        $this->assertEquals('v6.5.6', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('sumx', $device->secure_power_app);
        $this->assertEquals('AP9630', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_shodan_snmpv3_data_5(): void
    {
        $parser = new SnmpParser();

        $data = file_get_contents("tests/fixtures/parsers/snmp/shodan_snmpv3_5.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP9630', $device->fingerprint);
        $this->assertEquals('v6.1.1', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertEquals('sumx', $device->secure_power_app);
        $this->assertEquals('AP9630', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

}
