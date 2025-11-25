<?php

namespace Tests\Unit\Services\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\IonParser;
use PHPUnit\Framework\TestCase;

class IonParserTest extends TestCase
{
    public function test_it_parses_bitsight_ion_data_1()
    {
        $parser = new IonParser();
        $rawData = file_get_contents(__DIR__ . '/../../../../fixtures/parsers/ion/bitsight_ion_1.json');

        $result = $parser->parse($rawData);

        $this->assertEquals("schneider_electric", $result->vendor);
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
        $this->assertIsArray($result->fingerprint_raw);
        $this->assertEmpty($result->fingerprint_raw);
    }

    public function test_it_parses_bitsight_ion_data_2()
    {
        $parser = new IonParser();
        $rawData = file_get_contents(__DIR__ . '/../../../../fixtures/parsers/ion/bitsight_ion_2.json');

        $result = $parser->parse($rawData);

        $this->assertEquals("schneider_electric", $result->vendor);
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
        $this->assertIsArray($result->fingerprint_raw);
        $this->assertEmpty($result->fingerprint_raw);
    }
    public function test_it_parses_bitsight_ion_data_3()
    {
        $parser = new IonParser();
        $rawData = file_get_contents(__DIR__ . '/../../../../fixtures/parsers/ion/bitsight_ion_3.json');

        $result = $parser->parse($rawData);

        $this->assertEquals("schneider_electric", $result->vendor);
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
        $this->assertIsArray($result->fingerprint_raw);
        $this->assertEmpty($result->fingerprint_raw);
    }
}
