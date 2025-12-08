<?php

namespace App\Console\Commands;

use App\Models\DetectedExposure;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDetectedExposuresTimestamps extends Command
{
    protected $signature = 'detected-exposures:fix-timestamps {--dry-run : Run without making changes}';

    protected $description = 'Fix first_detected_at and last_detected_at timestamps by querying vendor tables';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        $this->info('Fixing detected_exposures timestamps from vendor data...');

        $totalCount = DetectedExposure::count();

        if ($totalCount === 0) {
            $this->warn('No detected_exposures records found.');

            return self::SUCCESS;
        }

        $this->info("Processing {$totalCount} detected_exposures records...");
        $progressBar = $this->output->createProgressBar($totalCount);
        $progressBar->start();

        $updatedCount = 0;
        $noDataCount = 0;

        DetectedExposure::chunk(1000, function ($exposures) use (&$updatedCount, &$noDataCount, $progressBar, $dryRun): void {
            foreach ($exposures as $exposure) {
                $timestamps = $this->getTimestampsFromVendors($exposure->ip, $exposure->port);

                if ($timestamps['min'] === null || $timestamps['max'] === null) {
                    $noDataCount++;
                    $progressBar->advance();

                    continue;
                }

                if (!$dryRun) {
                    $exposure->update([
                        'first_detected_at' => $timestamps['min'],
                        'last_detected_at' => $timestamps['max'],
                    ]);
                }

                $updatedCount++;
                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info("Would update {$updatedCount} records (DRY RUN - no changes made).");
        } else {
            $this->info("Updated {$updatedCount} records successfully.");
        }

        if ($noDataCount > 0) {
            $this->warn("Skipped {$noDataCount} records (no vendor data found).");
        }

        return self::SUCCESS;
    }

    private function getTimestampsFromVendors(string $ip, int $port): array
    {
        $allTimestamps = [];

        // Query Bitsight
        $bitsightTimestamps = DB::table('bitsight_exposed_assets')
            ->where('ip', $ip)
            ->where('port', $port)
            ->pluck('detected_at')
            ->toArray();

        $allTimestamps = array_merge($allTimestamps, $bitsightTimestamps);

        // Query Shodan
        $shodanTimestamps = DB::table('shodan_exposed_assets')
            ->where('ip', $ip)
            ->where('port', $port)
            ->pluck('detected_at')
            ->toArray();

        $allTimestamps = array_merge($allTimestamps, $shodanTimestamps);

        // Query Censys
        $censysTimestamps = DB::table('censys_exposed_assets')
            ->where('ip', $ip)
            ->where('port', $port)
            ->pluck('detected_at')
            ->toArray();

        $allTimestamps = array_merge($allTimestamps, $censysTimestamps);

        // Filter out null values and calculate min/max
        $allTimestamps = array_filter($allTimestamps);

        if (empty($allTimestamps)) {
            return ['min' => null, 'max' => null];
        }

        return [
            'min' => min($allTimestamps),
            'max' => max($allTimestamps),
        ];
    }
}
