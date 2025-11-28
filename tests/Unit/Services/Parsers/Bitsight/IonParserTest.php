<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\IonParser;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Services\Parsers\ParserTestCase;

class IonParserTest extends ParserTestCase
{
    public function test_it_parses_bitsight_ion_data_1()
    {
        $parser = new IonParser();

        $data = file_get_contents(__DIR__ . '/../../../../fixtures/parsers/ion/bitsight_ion_1.json');

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals("schneider_electric", $device->vendor);
        $this->assertEquals('7550', $device->fingerprint);
        $this->assertEquals('7550V350', $device->version);
        $this->assertEquals('PI-0807A008-01', $device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertEquals('[{"fingerprint":"7550","annotation":"cpe:2.3:h:schneider-electric:powerlogic_ion7550:-:*:*:*:*:*:*:*"}]', $device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ion_data_2()
    {
        $parser = new IonParser();

        $data = file_get_contents(__DIR__ . '/../../../../fixtures/parsers/ion/bitsight_ion_2.json');

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals("schneider_electric", $device->vendor);
        $this->assertEquals('7650', $device->fingerprint);
        $this->assertEquals('7650V371s', $device->version);
        $this->assertEquals('MJ-1408A459-04', $device->sn);
        $this->assertEquals('00:60:78:04:40:69', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertEquals('[{"fingerprint":"7650","annotation":"cpe:2.3:h:schneider-electric:powerlogic_ion7650:-:*:*:*:*:*:*:*"}]', $device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ion_data_3()
    {
        $parser = new IonParser();

        $data = file_get_contents(__DIR__ . '/../../../../fixtures/parsers/ion/bitsight_ion_3.json');

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals("schneider_electric", $device->vendor);
        $this->assertEquals('<Password Protected>', $device->fingerprint);
        $this->assertEquals('<Password Protected>', $device->version);
        $this->assertEquals('<Password Protected>', $device->sn);
        $this->assertEquals('<Password Protected>', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertNull($device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ion_data_4()
    {
        $parser = new IonParser();

        $data = file_get_contents(__DIR__ . '/../../../../fixtures/parsers/ion/bitsight_ion_4.json');

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals("schneider_electric", $device->vendor);
        $this->assertEquals('8650', $device->fingerprint);
        $this->assertEquals('8650V409', $device->version);
        $this->assertEquals('MW-1511A101-02', $device->sn);
        $this->assertEquals('00:60:78:05:04:8a', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertEquals('[{"fingerprint":"8650","annotation":"cpe:2.3:h:schneider-electric:powerlogic_ion8650:-:*:*:*:*:*:*:*"}]', $device->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ion_data_5()
    {
        $parser = new IonParser();

        $data = file_get_contents(__DIR__ . '/../../../../fixtures/parsers/ion/bitsight_ion_5.json');

        $result = $parser->parse($data);
        $this->assertAllDevices($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $device = $result[0];

        $this->assertEquals("schneider_electric", $device->vendor);
        $this->assertEquals('7400', $device->fingerprint);
        $this->assertEquals('004.005.000', $device->version);
        $this->assertEquals('MR-2402A255-03', $device->sn);
        $this->assertEquals('00:60:78:19:8f:cf', $device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertEquals('[{"fingerprint":"7400","annotation":"cpe:2.3:h:schneider-electric:powerlogic_ion7400:-:*:*:*:*:*:*:*"}]', $device->fingerprint_raw);
    }
}
