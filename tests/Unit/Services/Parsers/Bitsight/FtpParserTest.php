<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\FtpParser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class FtpParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_ftp_data_1(): void
    {
        $parser = new FtpParser();

        $data = file_get_contents("tests/fixtures/parsers/ftp/bitsight_ftp_1.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7900', $device->fingerprint);
        $this->assertEquals('v3.7.0', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertEquals('AP7900', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ftp_data_2(): void
    {
        $parser = new FtpParser();

        $data = file_get_contents("tests/fixtures/parsers/ftp/bitsight_ftp_2.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7954', $device->fingerprint);
        $this->assertEquals('v3.9.2', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertEquals('AP7954', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ftp_data_3(): void
    {
        $parser = new FtpParser();

        $data = file_get_contents("tests/fixtures/parsers/ftp/bitsight_ftp_3.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7932', $device->fingerprint);
        $this->assertEquals('v3.9.2', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertEquals('AP7932', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ftp_data_4(): void
    {
        $parser = new FtpParser();

        $data = file_get_contents("tests/fixtures/parsers/ftp/bitsight_ftp_4.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7900', $device->fingerprint);
        $this->assertEquals('v3.7.0', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertEquals('AP7900', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ftp_data_5(): void
    {
        $parser = new FtpParser();

        $data = file_get_contents("tests/fixtures/parsers/ftp/bitsight_ftp_5.json");

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals('Schneider Electric', $device->vendor);
        $this->assertEquals('AP7901', $device->fingerprint);
        $this->assertEquals('v3.7.3', $device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertEquals('AP7901', $device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

}
