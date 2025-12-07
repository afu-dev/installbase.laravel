<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

class SnmpParser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        if (!str_starts_with($this->rawData, 'SNMP:')) {
            $otherParser = new OtherParser();
            return $otherParser->parse($this->rawData);
        }

        $snmpData = [];

        $lines = explode("\n  ", $this->rawData);
        foreach ($lines as $line) {
            if (str_starts_with($line, "  ") || $line === "SNMP:") {
                // ignore line with version number (starts with 2 spaces)
                // or line containing only "SNMP:"
                continue;
            }

            [$key, $value] = explode(':', $line, 2);
            $snmpData[trim($key)] = trim($value);
        }

        if (empty($snmpData["Description"]) || !str_starts_with($snmpData["Description"], "APC")) {
            $otherParser = new OtherParser();
            return $otherParser->parse($this->rawData);
        }

        $start = strpos($snmpData["Description"], "(") + 1;
        $description = substr($snmpData["Description"], $start, strpos($snmpData["Description"], ")") - $start);
        preg_match_all('/(\w+):\s*([\S]+)/', $description, $matches);
        $snmpValues = array_combine($matches[1], $matches[2]);

        $securePowerApp = null;
        if (!empty($snmpValues["AN1"])) {
            $securePowerApp = substr_count($snmpValues["AN1"], "_") === 3
                ? explode("_", $snmpValues["AN1"])[2]
                : $snmpValues["AN1"];
        }

        return [
            new ParsedDeviceData(
                vendor: 'Schneider Electric',
                fingerprint: $snmpValues["MN"] ?? null,
                version: $snmpValues["PF"] ?? null,
                secure_power_app: $securePowerApp,
                nmc_card_num: $snmpValues["MN"] ?? null,
            ),
        ];
    }
}
