<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts device information from the SNMP protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * This parser checks for APC devices by detecting "APC" in the snmp_data field.
 *
 * If "APC" is found:
 * - Extracts MN value for fingerprint (e.g., AP7920, NBRK0200)
 * - Extracts PF value for version (e.g., v3.9.2)
 * - Extracts AN1 value for secure_power_app (e.g., apc_hw02_rpdu_392.bin)
 * - If MN starts with "AP" followed by exactly 4 digits, extracts the 4 digits for nmc_card_num
 * - Returns ParsedDeviceData with vendor='Schneider Electric'
 *
 * If "APC" is NOT found:
 * - Delegates to OtherParser
 *
 * ---
 *
 * Snmp Key Frequency:
 * +--------------+--------+------------+
 * | Key          | Count  | Percentage |
 * +--------------+--------+------------+
 * | snmp_data    | 65,476 | 94.62%     |
 * | hash_data    | 51,270 | 74.09%     |
 * | product_name | 34,659 | 50.08%     |
 * +--------------+--------+------------+
 */
class SnmpParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        // Extract nested SNMP data (handles both "Snmp" and "snmp" keys)
        $snmpData = $this->extractNested(["Snmp", "snmp"], "snmp_data");

        // Early return: delegate to OtherParser if snmp_data is empty
        if (empty($snmpData)) {
            $otherParser = new OtherParser();
            return $otherParser->parse($this->rawData);
        }

        // Early return: delegate to OtherParser if APC not present
        if (!str_contains((string) $snmpData, 'APC')) {
            $otherParser = new OtherParser();
            return $otherParser->parse($this->rawData);
        }

        // Extract MN value (model/fingerprint)
        $fingerprint = null;
        if (preg_match('/MN:(\S+)/', (string) $snmpData, $mnMatches)) {
            $fingerprint = $mnMatches[1];
        }

        // Extract PF value (version)
        $version = null;
        if (preg_match('/PF:(\S+)/', (string) $snmpData, $pfMatches)) {
            $version = $pfMatches[1];
        }

        // Extract AN1 value (secure_power_app)
        $securePowerApp = null;
        if (preg_match('/AN1:(\S+)/', (string) $snmpData, $an1Matches)) {
            $securePowerApp = $an1Matches[1];
        }

        // Extract nmc_card_num if MN starts with "AP" followed by exactly 4 digits
        $nmcCardNum = null;
        if ($fingerprint && preg_match('/^AP\d{4}$/', $fingerprint)) {
            // Extract the 4 digits from the AP\d{4} pattern
            if (preg_match('/AP(\d{4})/', $fingerprint, $cardMatches)) {
                $nmcCardNum = $cardMatches[1];
            }
        }

        return [
            new ParsedDeviceData(
                vendor: 'Schneider Electric',
                fingerprint: $fingerprint,
                version: $version,
                secure_power_app: $securePowerApp,
                nmc_card_num: $nmcCardNum,
            ),
        ];
    }
}
