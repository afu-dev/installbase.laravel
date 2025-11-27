<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\CodesysParser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class CodesysParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_codesys_data_1(): void
    {
        $parser = new CodesysParser();

        $data = file_get_contents("tests/fixtures/parsers/codesys/bitsight_codesys_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Unknown', $device->vendor);
        $this->assertEquals('3S-Smart Software Solutions', $device->fingerprint);
        $this->assertEquals('4.9.47-rt37-w02.02.00_01+14 [ru', $device->version);
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

    public function test_it_parses_bitsight_codesys_data_2(): void
    {
        $parser = new CodesysParser();

        $data = file_get_contents("tests/fixtures/parsers/codesys/bitsight_codesys_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(4, $result);
        $deviceIds = ["0080F40A9E7D", "0080F40A9E84", "0080F40A9E85", "0080F40A9E81"];
        foreach($deviceIds as $deviceId) {
            $this->assertArrayHasKey($deviceId, $result);
            $device = $result[$deviceId];

            $this->assertEquals('Schneider Electric', $device->vendor);
            $this->assertEquals('TM241CE40T_U', $device->fingerprint);
            $this->assertEquals('4.0.6.26', $device->version);
            $this->assertEquals('', $device->sn);
            $this->assertNull($device->device_mac);
            $this->assertNull($device->modbus_project_info);
            $this->assertNull($device->opc_ua_security_policy);
            $this->assertNull($device->is_guest_account_active);
            $this->assertNull($device->registration_info);
            $this->assertNull($device->secure_power_app);
            $this->assertNull($device->nmc_card_num);
            $this->assertEquals('[{"fingerprint":"TM241CE40T_U","annotation":"Modicon Family M241"}]', $device->fingerprint_raw);
        }
    }

    public function test_it_parses_bitsight_codesys_data_3(): void
    {
        $parser = new CodesysParser();

        $data = file_get_contents("tests/fixtures/parsers/codesys/bitsight_codesys_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Unknown', $device->vendor);
        $this->assertEquals('3S-Smart Software Solutions', $device->fingerprint);
        $this->assertEquals('Nucleus PLUS version unknown', $device->version);
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

    public function test_it_parses_bitsight_codesys_data_4(): void
    {
        $parser = new CodesysParser();

        $data = file_get_contents("tests/fixtures/parsers/codesys/bitsight_codesys_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Unknown', $device->vendor);
        $this->assertEquals('3S-Smart Software Solutions', $device->fingerprint);
        $this->assertEquals('Nucleus PLUS version unknown', $device->version);
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

    public function test_it_parses_bitsight_codesys_data_5(): void
    {
        $parser = new CodesysParser();

        $data = file_get_contents("tests/fixtures/parsers/codesys/bitsight_codesys_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Unknown', $device->vendor);
        $this->assertNull($device->fingerprint);
        $this->assertEquals('3.0 or higher', $device->version);
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
