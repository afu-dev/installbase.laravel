<?php

namespace App\Console\Commands;

use App\Models\DetectedExposure;
use Illuminate\Console\Command;

class ExportDetectedExposures extends Command
{
    protected $signature = 'detected-exposures:export {--path=storage/app : Output directory path}';

    protected $description = 'Export detected_exposures with attribution data to a single CSV file';

    public function handle(): int
    {
        $path = $this->option('path');

        // Ensure directory exists
        if (! is_dir($path)) {
            $this->error("Directory does not exist: {$path}");

            return self::FAILURE;
        }

        $this->info('Exporting detected_exposures with attribution data to CSV...');
        $this->newLine();

        // Export detected_exposures with attribution data
        $this->exportDetectedExposures($path);

        $this->newLine();
        $this->info('Export completed successfully!');

        return self::SUCCESS;
    }

    private function exportDetectedExposures(string $path): void
    {
        $filePath = rtrim($path, '/').'/detected_exposures.csv';
        $file = fopen($filePath, 'w');

        if ($file === false) {
            $this->error("Failed to create file: {$filePath}");

            return;
        }

        // Write header
        fputcsv($file, [
            'ip',
            'port',
            'source',
            'module',
            'transport',
            'first_detected_at',
            'last_detected_at',
            'entity',
            'sector',
            'domain',
            'hostnames',
            'isp',
            'asn',
            'whois',
            'city',
            'country_code',
            'source_of_attribution',
            'last_exposure_at',
        ], ',', '"', '');

        $totalCount = DetectedExposure::count();
        $this->info("Exporting {$totalCount} detected_exposures records...");
        $progressBar = $this->output->createProgressBar($totalCount);
        $progressBar->start();

        $exportedCount = 0;

        DetectedExposure::with('attribution')->chunk(1000, function ($exposures) use ($file, &$exportedCount, $progressBar) {
            foreach ($exposures as $exposure) {
                fputcsv($file, [
                    $exposure->ip,
                    $exposure->port,
                    $exposure->source?->value,
                    $exposure->module,
                    $exposure->transport,
                    $exposure->first_detected_at,
                    $exposure->last_detected_at,
                    $exposure->attribution?->entity,
                    $exposure->attribution?->sector,
                    $exposure->attribution?->domain,
                    $exposure->attribution?->hostnames,
                    $exposure->attribution?->isp,
                    $exposure->attribution?->asn,
                    $exposure->attribution?->whois,
                    $exposure->attribution?->city,
                    $exposure->attribution?->country_code,
                    $exposure->attribution?->source_of_attribution,
                    $exposure->attribution?->last_exposure_at,
                ], ',', '"', '');

                $exportedCount++;
                $progressBar->advance();
            }
        });

        fclose($file);

        $progressBar->finish();
        $this->newLine();
        $this->info("Exported {$exportedCount} records to: {$filePath}");
    }
}
