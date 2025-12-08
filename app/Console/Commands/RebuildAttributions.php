<?php

namespace App\Console\Commands;

use App\Models\Attribution;
use App\Models\BitsightExposedAsset;
use App\Models\DetectedExposure;
use App\Models\ShodanExposedAsset;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class RebuildAttributions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attributions:rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild attributions table from detected_exposures and vendor data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $startTime = microtime(true);
        $this->info("--- Rebuilding Attributions ---");

        // Get all unique IPs from detected_exposures
        $uniqueIps = DetectedExposure::query()
            ->distinct()
            ->pluck('ip');

        $total = $uniqueIps->count();
        $this->info("Processing {$total} unique IPs");

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->setFormat(ProgressBar::FORMAT_VERBOSE);

        foreach ($uniqueIps as $ip) {
            $progressBar->setMessage("Processing IP: {$ip}");

            // Get attribution data from vendor tables (priority: Bitsight > Shodan)
            $bitsightRecord = BitsightExposedAsset::where('ip', $ip)->first();
            $shodanRecord = ShodanExposedAsset::where('ip', $ip)->first();

            // Priority merge (Bitsight > Shodan)
            $entity = $bitsightRecord?->entity ?? $shodanRecord?->entity;
            $isp = $bitsightRecord?->isp ?? $shodanRecord?->isp;
            $asn = $bitsightRecord?->asn ?? $shodanRecord?->asn;
            $city = $bitsightRecord?->city ?? $shodanRecord?->city;
            $countryCode = $bitsightRecord?->country_code ?? $shodanRecord?->country_code;

            // Merge hostnames from vendor tables
            $bitsightHostnames = $bitsightRecord ? preg_split('/[,;]\s*/', $bitsightRecord->hostnames ?? '') : [];
            $shodanHostnames = $shodanRecord ? preg_split('/[,;]\s*/', $shodanRecord->hostnames ?? '') : [];
            $allHostnames = array_filter(array_merge($bitsightHostnames, $shodanHostnames));
            $allHostnames = array_unique($allHostnames);
            sort($allHostnames);
            $hostnames = empty($allHostnames) ? null : implode(',', $allHostnames);

            // Get last_exposure_at from detected_exposures (MAX of last_detected_at for this IP)
            $lastExposureAt = DetectedExposure::where('ip', $ip)
                ->max('last_detected_at');

            // Upsert attribution
            $data = array_filter([
                'entity' => $entity,
                'hostnames' => $hostnames,
                'isp' => $isp,
                'asn' => $asn,
                'city' => $city,
                'country_code' => $countryCode,
                'last_exposure_at' => $lastExposureAt,
            ], fn ($value) => $value !== null);

            Attribution::updateOrCreate(['ip' => $ip], $data);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $executionTime = microtime(true) - $startTime;
        $this->info("Completed: {$total} attributions processed in " . round($executionTime, 2) . " seconds");

        return Command::SUCCESS;
    }
}
