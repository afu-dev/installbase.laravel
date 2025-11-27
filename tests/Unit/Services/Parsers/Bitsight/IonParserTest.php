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
        $this->assertNull($device->fingerprint);
        $this->assertNull($device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertIsArray($device->fingerprint_raw);
        $this->assertEmpty($device->fingerprint_raw);
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
        $this->assertNull($device->fingerprint);
        $this->assertNull($device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertIsArray($device->fingerprint_raw);
        $this->assertEmpty($device->fingerprint_raw);
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
        $this->assertNull($device->fingerprint);
        $this->assertNull($device->version);
        $this->assertNull($device->sn);
        $this->assertNull($device->device_mac);
        $this->assertNull($device->modbus_project_info);
        $this->assertNull($device->opc_ua_security_policy);
        $this->assertNull($device->is_guest_account_active);
        $this->assertNull($device->registration_info);
        $this->assertNull($device->secure_power_app);
        $this->assertNull($device->nmc_card_num);
        $this->assertIsArray($device->fingerprint_raw);
        $this->assertEmpty($device->fingerprint_raw);
    }
}
