<?php

namespace App\Services\Parsers\Shodan;

use App\Services\Parsers\AbstractRawDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * Apcupsd Key Frequency:
 * +----------+-------+------------+
 * | Key      | Count | Percentage |
 * +----------+-------+------------+
 * | APC      | 4,660 | 199.06%    |
 * | DATE     | 2,341 | 100%       |
 * | HOSTNAME | 2,341 | 100%       |
 * | VERSION  | 2,341 | 100%       |
 * | CABLE    | 2,341 | 100%       |
 * | UPSMODE  | 2,341 | 100%       |
 * | STATUS   | 2,341 | 100%       |
 * | MBATTCHG | 2,341 | 100%       |
 * | MINTIMEL | 2,341 | 100%       |
 * | MAXTIME  | 2,341 | 100%       |
 * | NUMXFERS | 2,341 | 100%       |
 * | TONBATT  | 2,341 | 100%       |
 * | XOFFBATT | 2,341 | 100%       |
 * | STATFLAG | 2,335 | 99.74%     |
 * | UPSNAME  | 2,296 | 98.08%     |
 * | MODEL    | 2,245 | 95.9%      |
 * | BCHARGE  | 2,245 | 95.9%      |
 * | TIMELEFT | 2,245 | 95.9%      |
 * | SERIALNO | 2,235 | 95.47%     |
 * | BATTV    | 2,223 | 94.96%     |
 * | FIRMWARE | 2,221 | 94.87%     |
 * | DRIVER   | 2,183 | 93.25%     |
 * | ALARMDEL | 1,922 | 82.1%      |
 * | LOADPCT  | 1,913 | 81.72%     |
 * | LINEV    | 1,910 | 81.59%     |
 * | BATTDATE | 1,902 | 81.25%     |
 * | LASTXFER | 1,892 | 80.82%     |
 * | LOTRANS  | 1,884 | 80.48%     |
 * | HITRANS  | 1,884 | 80.48%     |
 * | SELFTEST | 1,819 | 77.7%      |
 * | SENSE    | 1,803 | 77.02%     |
 * | NOMBATTV | 1,766 | 75.44%     |
 * | XONBATT  | 1,752 | 74.84%     |
 * | MANDATE  | 1,450 | 61.94%     |
 * | OUTPUTV  | 1,416 | 60.49%     |
 * | LINEFREQ | 1,416 | 60.49%     |
 * | NOMOUTV  | 1,413 | 60.36%     |
 * | ITEMP    | 1,356 | 57.92%     |
 * | RETPCT   | 1,294 | 55.28%     |
 * | DSHUTD   | 1,120 | 47.84%     |
 * | DWAKE    | 1,119 | 47.8%      |
 * | STESTI   | 1,113 | 47.54%     |
 * | DLOWBATT | 975   | 41.65%     |
 * | MAXLINEV | 971   | 41.48%     |
 * | MINLINEV | 971   | 41.48%     |
 * | EXTBATTS | 572   | 24.43%     |
 * | NOMINV   | 551   | 23.54%     |
 * | NOMPOWER | 494   | 21.1%      |
 * | MASTER   | 301   | 12.86%     |
 * | BADBATTS | 214   | 9.14%      |
 * | APCMODEL | 144   | 6.15%      |
 * | DIPSW    | 139   | 5.94%      |
 * | SHARE    | 94    | 4.02%      |
 * | HUMIDITY | 67    | 2.86%      |
 * | AMBTEMP  | 67    | 2.86%      |
 * | RELEASE  | 20    | 0.85%      |
 * | LOADAPNT | 7     | 0.3%       |
 * | OUTCURNT | 7     | 0.3%       |
 * | NOMAPNT  | 7     | 0.3%       |
 * +----------+-------+------------+
 */
class ApcupsdParser extends AbstractRawDataParser
{
    protected function parseData(): array
    {
        $apcuData = [];
        $lines = explode("\n", trim($this->rawData));
        foreach ($lines as $line) {
            [$key, $value] = explode(':', $line, 2);
            $apcuData[trim($key)] = trim($value);
        }
        $vendor = null;
        foreach ($apcuData as $key => $value) {
            if (!in_array($key, [])) { // TODO: ready to integrate
                continue;
            }
            $vendor = $this->detectBrand($value);
            if ($vendor !== null) {
                break;
            }
        }

        return [
            new ParsedDeviceData(
                vendor: $vendor ?? "unknown",
                fingerprint: $apcuData["MODEL"] ?? null,
                version: $apcuData["VERSION"] ?? null,
                sn: $apcuData["SERIALNO"] ?? null,
            ),
        ];
    }
}
