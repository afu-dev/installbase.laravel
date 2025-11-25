<?php

namespace Tests\Unit\Parsers\Bitsight;

use App\Services\Parsers\Bitsight\IonParser;
use PHPUnit\Framework\TestCase;

class IonParserTest extends TestCase
{
    public function test_it_parses_bitsight_ion_data_1()
    {
        $parser = new IonParser();

        $rawData = '{"ip_str":"186.103.131.42","port":"7000","transport":"","module":"","date":"2025-08-24","protocol_type":"IT","bacnet":"","dnp3":"","ethernetip":"","fox":"","iec-61850":"","knx":"","modbus":"","opc-ua":"","codesys":"","iec-104":"","ion":"","apcupsd":"","http":"","ssl":"","ftp":"","ms-sql-monitor":"","snmp":"","mdns":"","telnet":"","ebo_info":"","vendor_name":"schneider_electric","vendor_match_criteria":"fingerprint","fingerprint":"[{\"fingerprint\":\"groma_custom_pycog2\",\"annotation\":\"cpe:2.3:o:schneider-electric:powerlogic_ion7650_firmware:-:*:*:*:*:*:*:*\"},{\"fingerprint\":\"groma_custom_pycog2\",\"annotation\":\"cpe:2.3:h:schneider-electric:powerlogic_ion7650:-:*:*:*:*:*:*:*\"}]","entity_name":"","entity_primary_domain":"","industry_sector":"","ci_sector":"","ci_sector_secondary":"","ci_sector_tertiary":"","entity_other_name":"COMPANIA MOLINERA VILLARRICA LIMITADA","entity_other_primary_domain":"tie.cl","entity_other_org_address":"","country_code":"CL","country_name":"Chile"}';

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

        $rawData = '{"ip_str":"186.103.131.42","port":"7700","transport":"tcp","module":"ion","date":"2025-08-22","protocol_type":"OT","bacnet":"","dnp3":"","ethernetip":"","fox":"","iec-61850":"","knx":"","modbus":"","opc-ua":"","codesys":"","iec-104":"","ion":"{\"secure_ion\":\"Not Supported (NAK)\",\"device_type\":\"<Password Protected>\",\"options\":\"<Password Protected>\",\"revision\":\"<Password Protected>\",\"serial_num\":\"<Password Protected>\",\"template\":\"<Password Protected>\",\"owner\":\"<Password Protected>\",\"tag1\":\"<Password Protected>\",\"tag2\":\"<Password Protected>\",\"mac_address\":\"<Password Protected>\",\"device_namespace\":\"<Password Protected>\",\"compliance\":\"<Password Protected>\",\"ion_version\":\"<Password Protected>\",\"metering_fw_revision\":\"<Password Protected>\",\"device_name\":\"<Password Protected>\"}","apcupsd":"","http":"","ssl":"","ftp":"","ms-sql-monitor":"","snmp":"","mdns":"","telnet":"","ebo_info":"","vendor_name":"schneider_electric","vendor_match_criteria":"fingerprint","fingerprint":"","entity_name":"","entity_primary_domain":"","industry_sector":"","ci_sector":"","ci_sector_secondary":"","ci_sector_tertiary":"","entity_other_name":"COMPANIA MOLINERA VILLARRICA LIMITADA","entity_other_primary_domain":"tie.cl","entity_other_org_address":"","country_code":"CL","country_name":"Chile"}';

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

        $rawData = '{"Apcupsd":"","Bacnet":"","Bacnet.Appsoft":"","Bacnet.Desc":"","Bacnet.Firmware":"","Bacnet.Instance Id":"","Bacnet.Location":"","Bacnet.Model":"","Bacnet.Name":"","Bacnet.Object":"","Ci Sector":"","Ci Sector Secondary":"","Ci Sector Tertiary":"","City":"","Codesys":"","Country Code":"US","Country Name":"United States","Date":"13\/07\/2025 00:00:00","Dnp3":"","Ebo Info":"","Entity Name":"","Entity Other Name":"Isotropic Networks, Inc.","Entity Other Org Address":"W2835 Krueger Rd, Lake Geneva, WI, US","Entity Other Primary Domain":"isosat.net","Entity Primary Domain":"","Ethernetip":"","Fingerprint":"[{\"fingerprint\":\"8650\",\"annotation\":\"cpe:2.3:h:schneider-electric:powerlogic_ion8650:-:*:*:*:*:*:*:*\"}]","Fox":"","Ftp":"","Http":"","Iec-104":"","Iec-61850":"","Industry Sector":"","Ion":"{\"secure_ion\":\"Not Supported (NAK)\",\"device_type\":\"8650\",\"options\":\"M8650B0C0H6E1A1A\",\"revision\":\"8650V409\",\"serial_num\":\"MW-1612A077-02\",\"template\":\"8650B_V403_FAC_9S_HM_Centro_PQ_V1.5.1.0.0.1\",\"owner\":\"DN_POSEIDO\",\"tag1\":\"GPO IND POSEID\",\"tag2\":\"G043XD\",\"mac_address\":\"00:60:78:06:7c:fd\",\"device_namespace\":\"EnterDeviceNamespaceHere\",\"compliance\":\"ION_COMPLIANT\",\"ion_version\":\"13\",\"metering_fw_revision\":\"1.4\",\"device_name\":\"EnterDeviceNameHere\"}","Ip Str":"100.42.4.9","Knx":"","Mdns":"","Modbus":"","Module":"ion","Ms-Sql-Monitor":"","Opc-Ua":"","Protocol Type":"OT","Snmp":"","Source":"bitsight","Source File":"2025_07_merged_events_schneider.csv","Ssl":"","Telnet":"","Transport":"tcp","Vendor":"schneider_electric","Port":"7700.0"}';

        $result = $parser->parse($rawData);

        $this->assertEquals("schneider_electric", $result->vendor);
    }
}
