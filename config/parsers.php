<?php

return [
    "map" => [
        // Bitsight parsers (14 modules)
        "bitsight.apcupsd" => App\Services\Parsers\Bitsight\ApcupsdParser::class,
        "bitsight.bacnet" => App\Services\Parsers\Bitsight\BacnetParser::class,
        "bitsight.codesys" => App\Services\Parsers\Bitsight\CodesysParser::class,
        "bitsight.dnp3" => App\Services\Parsers\Bitsight\Dnp3Parser::class,
        "bitsight.ethernetip" => App\Services\Parsers\Bitsight\EthernetipParser::class,
        "bitsight.ftp" => App\Services\Parsers\Bitsight\FtpParser::class,
        "bitsight.iec-104" => App\Services\Parsers\Bitsight\Iec104Parser::class,
        "bitsight.iec-61850" => App\Services\Parsers\Bitsight\Iec61850Parser::class,
        "bitsight.ion" => App\Services\Parsers\Bitsight\IonParser::class,
        "bitsight.knx" => App\Services\Parsers\Bitsight\KnxParser::class,
        "bitsight.modbus" => App\Services\Parsers\Bitsight\ModbusParser::class,
        "bitsight.opc-ua" => App\Services\Parsers\Bitsight\OpcUaParser::class,
        "bitsight.other" => App\Services\Parsers\Bitsight\OtherParser::class,
        "bitsight.snmp" => App\Services\Parsers\Bitsight\SnmpParser::class,
        "bitsight.snmp_v2" => App\Services\Parsers\Bitsight\SnmpParser::class,
        "bitsight.snmp_v3" => App\Services\Parsers\Bitsight\SnmpParser::class,

        // Shodan parsers (7 modules + SNMP variants)
        "shodan.apcupsd" => App\Services\Parsers\Shodan\ApcupsdParser::class,
        "shodan.bacnet" => App\Services\Parsers\Shodan\BacnetParser::class,
        "shodan.ethernetip" => App\Services\Parsers\Shodan\EthernetipParser::class,
        "shodan.ftp" => App\Services\Parsers\Shodan\FtpParser::class,
        "shodan.iec-61850" => App\Services\Parsers\Shodan\Iec61850Parser::class,
        "shodan.modbus" => App\Services\Parsers\Shodan\ModbusParser::class,
        "shodan.snmp" => App\Services\Parsers\Shodan\SnmpParser::class,
        "shodan.snmp_v2" => App\Services\Parsers\Shodan\SnmpParser::class,
        "shodan.snmp_v3" => App\Services\Parsers\Shodan\SnmpParser::class,

        // Censys parsers (14 modules - future-proofing)
        "censys.apcupsd" => App\Services\Parsers\Censys\ApcupsdParser::class,
        "censys.bacnet" => App\Services\Parsers\Censys\BacnetParser::class,
        "censys.codesys" => App\Services\Parsers\Censys\CodesysParser::class,
        "censys.dnp3" => App\Services\Parsers\Censys\Dnp3Parser::class,
        "censys.ethernetip" => App\Services\Parsers\Censys\EthernetipParser::class,
        "censys.ftp" => App\Services\Parsers\Censys\FtpParser::class,
        "censys.iec-104" => App\Services\Parsers\Censys\Iec104Parser::class,
        "censys.iec-61850" => App\Services\Parsers\Censys\Iec61850Parser::class,
        "censys.ion" => App\Services\Parsers\Censys\IonParser::class,
        "censys.knx" => App\Services\Parsers\Censys\KnxParser::class,
        "censys.modbus" => App\Services\Parsers\Censys\ModbusParser::class,
        "censys.opc-ua" => App\Services\Parsers\Censys\OpcUaParser::class,
        "censys.other" => App\Services\Parsers\Censys\OtherParser::class,
        "censys.snmp" => App\Services\Parsers\Censys\SnmpParser::class,
    ],
];
