<?php

namespace Tests\Unit\Services\Parsers\Shodan;

use App\Services\Parsers\Shodan\ModbusParser;
use Tests\Unit\Services\Parsers\ParserTestCase;

class ModbusParserTest extends ParserTestCase
{
    public function test_it_parses_shodan_modbus_data_1(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/shodan_modbus_1.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(3, $result);
        $device0 = $result[0];
        $this->assertArrayHasKey(1, $result);
        $device1 = $result[1];
        $this->assertArrayHasKey(255, $result);
        $device255 = $result[255];

        $this->assertEquals("unknown", $device0->vendor);

        $this->assertEquals('Schneider Electric', $device1->vendor);
        $this->assertEquals('METSEPM3250', $device1->fingerprint);
        $this->assertEquals('001.007.002', $device1->version);
        $this->assertNull($device1->sn);
        $this->assertNull($device1->device_mac);
        $this->assertNull($device1->modbus_project_info);
        $this->assertNull($device1->opc_ua_security_policy);
        $this->assertNull($device1->is_guest_account_active);
        $this->assertNull($device1->registration_info);
        $this->assertNull($device1->secure_power_app);
        $this->assertNull($device1->nmc_card_num);
        $this->assertNull($device1->fingerprint_raw);

        $this->assertEquals('Schneider Electric', $device255->vendor);
        $this->assertEquals('PAS600L', $device255->fingerprint);
        $this->assertEquals('001.006.000', $device255->version);
        $this->assertNull($device255->sn);
        $this->assertNull($device255->device_mac);
        $this->assertNull($device255->modbus_project_info);
        $this->assertNull($device255->opc_ua_security_policy);
        $this->assertNull($device255->is_guest_account_active);
        $this->assertNull($device255->registration_info);
        $this->assertNull($device255->secure_power_app);
        $this->assertNull($device255->nmc_card_num);
        $this->assertNull($device255->fingerprint_raw);
    }

    public function test_it_parses_shodan_modbus_data_2(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/shodan_modbus_2.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayHasKey(255, $result);

        foreach ($result as $device) {
            $this->assertEquals('Schneider Electric', $device->vendor);
            $this->assertEquals('PM5563', $device->fingerprint);
            $this->assertEquals('V2.4', $device->version);
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

    public function test_it_parses_shodan_modbus_data_3(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/shodan_modbus_3.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(4, $result);

        $this->assertArrayHasKey(0, $result);
        $device0 = $result[0];
        $this->assertArrayHasKey(1, $result);
        $device1 = $result[1];
        $this->assertArrayHasKey(127, $result);
        $device127 = $result[127];
        $this->assertArrayHasKey(255, $result);
        $device255 = $result[255];

        foreach ([$device0, $device1] as $device) {
            $this->assertEquals("unknown", $device->vendor);
            $this->assertNull($device->fingerprint);
            $this->assertNull($device->version);
        }

        $this->assertEquals('HUAWEI', $device127->vendor);
        $this->assertEquals('Smart Logger', $device127->fingerprint);
        $this->assertEquals('300R001C00SPC110', $device127->version);

        $this->assertEquals('TELEMECANIQUE', $device255->vendor);
        $this->assertEquals('TWDLCAE40DRF', $device255->fingerprint);
        $this->assertEquals('05.40', $device255->version);

        foreach ([$device0, $device1, $device127, $device255] as $device) {
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

    public function test_it_parses_shodan_modbus_data_4(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/shodan_modbus_4.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(0, $result);
        $device0 = $result[0];
        $this->assertArrayHasKey(1, $result);
        $device1 = $result[1];
        $this->assertArrayHasKey(255, $result);
        $device255 = $result[255];

        $this->assertEquals('Schneider Electric', $device0->vendor);
        $this->assertEquals('BME H58 2040', $device0->fingerprint);
        $this->assertEquals('v03.20', $device0->version);
        $this->assertNull($device0->sn);
        $this->assertNull($device0->device_mac);
        $this->assertEquals('Project - V12.0   WIN-O1VIKFDHJ4D C:\USERS\ADMINISTRATOR\DOWNLOADS\SMF.STU d3D3rpgSZ', $device0->modbus_project_info);
        $this->assertNull($device0->opc_ua_security_policy);
        $this->assertNull($device0->is_guest_account_active);
        $this->assertNull($device0->registration_info);
        $this->assertNull($device0->secure_power_app);
        $this->assertNull($device0->nmc_card_num);
        $this->assertNull($device0->fingerprint_raw);

        foreach ([$device1, $device255] as $device) {
            $this->assertEquals('Schneider Electric', $device->vendor);
            $this->assertEquals('BME H58 2040', $device->fingerprint);
            $this->assertEquals('v03.20', $device->version);
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

    public function test_it_parses_shodan_modbus_data_5(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/shodan_modbus_5.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(0, $result);
        $device0 = $result[0];
        $this->assertArrayHasKey(1, $result);
        $device1 = $result[1];
        $this->assertArrayHasKey(255, $result);
        $device255 = $result[255];

        $this->assertEquals('Schneider Electric', $device0->vendor);
        $this->assertEquals('BME H58 2040', $device0->fingerprint);
        $this->assertEquals('v03.10', $device0->version);
        $this->assertNull($device0->sn);
        $this->assertNull($device0->device_mac);
        $this->assertEquals('Project - 5DDB1779D HHIyp26qrUU= cmA7j30G9pbYoFxG9pvIYzsS27XJOsDSpjfhJ2534uQ=    V13.1', $device0->modbus_project_info);
        $this->assertNull($device0->opc_ua_security_policy);
        $this->assertNull($device0->is_guest_account_active);
        $this->assertNull($device0->registration_info);
        $this->assertNull($device0->secure_power_app);
        $this->assertNull($device0->nmc_card_num);
        $this->assertNull($device0->fingerprint_raw);

        foreach ([$device1, $device255] as $device) {
            $this->assertEquals('Schneider Electric', $device->vendor);
            $this->assertEquals('BME H58 2040', $device->fingerprint);
            $this->assertEquals('v03.10', $device->version);
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

    public function test_it_parses_shodan_modbus_data_6(): void
    {
        $parser = new ModbusParser();

        $data = file_get_contents("tests/fixtures/parsers/modbus/shodan_modbus_6.txt");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(0, $result);
        $device0 = $result[0];
        $this->assertArrayHasKey(1, $result);
        $device1 = $result[1];
        $this->assertArrayHasKey(255, $result);
        $device255 = $result[255];

        foreach ([$device0, $device1, $device255] as $device) {
            $this->assertEquals('unknown', $device->vendor);
            $this->assertNull($device->fingerprint);
            $this->assertNull($device0->version);
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

}
