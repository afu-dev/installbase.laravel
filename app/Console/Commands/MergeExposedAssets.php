<?php

namespace App\Console\Commands;

use App\Models\Attribution;
use App\Models\BitsightExposedAsset;
use App\Models\CensysExposedAsset;
use App\Models\DetectedExposure;
use App\Models\Scan;
use App\Models\ShodanExposedAsset;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class MergeExposedAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exposed-assets:merge {--scan-id= : Specific scan ID to merge}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge vendor exposed assets into detected_exposures and attributions tables';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $startTime = microtime(true);

        // Get scan (from option or latest)
        $scan = $this->getScan();
        if (!$scan) {
            $this->error('No scan found.');

            return Command::FAILURE;
        }

        $this->info("Processing Scan ID: {$scan->id}");

        // Verify all executions are finished
        $executions = $scan->executions;
        $unfinishedCount = $executions->whereNull('finished_at')->count();

        if ($unfinishedCount > 0) {
            $this->error("Scan has {$unfinishedCount} unfinished execution(s). Cannot merge until all executions complete.");

            return Command::FAILURE;
        }

        $this->info("Processing {$executions->count()} execution(s)");

        // Query vendor data from scan's executions
        $executionIds = $executions->pluck('id');

        $bitsightAssets = BitsightExposedAsset::whereIn('execution_id', $executionIds)->get();
        $shodanAssets = ShodanExposedAsset::whereIn('execution_id', $executionIds)->get();
        $censysAssets = CensysExposedAsset::whereIn('execution_id', $executionIds)->get();

        $this->info("Found {$bitsightAssets->count()} Bitsight assets, {$shodanAssets->count()} Shodan assets, {$censysAssets->count()} Censys assets");

        // Group by IP:Port for detected_exposures
        $groupedByIpPort = $this->groupByIpPort($bitsightAssets, $shodanAssets, $censysAssets);
        $this->info("Processing " . count($groupedByIpPort) . ' unique IP:Port combination(s)');

        // Group by IP for attributions
        $groupedByIp = $this->groupByIp($bitsightAssets, $shodanAssets, $censysAssets);
        $this->info("Processing " . count($groupedByIp) . ' unique IP(s)');

        // Merge detected_exposures (IP:Port level)
        $exposureCreated = 0;
        $exposureUpdated = 0;

        foreach ($groupedByIpPort as $key => $vendors) {
            [$ip, $port] = explode(':', $key);
            $port = (int)$port;

            $existing = DetectedExposure::forIpPort($ip, $port)->first();

            $exposureData = $this->mergeExposureData($ip, $port, $vendors, $existing);

            if ($existing) {
                $existing->update($exposureData);
                $exposureUpdated++;
            } else {
                DetectedExposure::create($exposureData);
                $exposureCreated++;
            }
        }

        // Merge attributions (IP level)
        $attributionCreated = 0;
        $attributionUpdated = 0;

        foreach ($groupedByIp as $ip => $vendors) {
            $existing = Attribution::forIp($ip)->first();

            $attributionData = $this->mergeAttributionData($ip, $vendors, $existing);

            if ($existing) {
                $existing->update($attributionData);
                $attributionUpdated++;
            } else {
                Attribution::create($attributionData);
                $attributionCreated++;
            }
        }

        $executionTime = round(microtime(true) - $startTime, 2);

        $this->info("Detected Exposures: {$exposureCreated} created, {$exposureUpdated} updated");
        $this->info("Attributions: {$attributionCreated} created, {$attributionUpdated} updated");
        $this->info("Execution time: {$executionTime}s");

        return Command::SUCCESS;
    }

    /**
     * Get scan from option or latest scan.
     */
    private function getScan(): ?Scan
    {
        if ($scanId = $this->option('scan-id')) {
            return Scan::find($scanId);
        }

        return Scan::latest()->first();
    }

    /**
     * Group vendor assets by IP:Port combination.
     *
     * @return array{bitsight: Collection<int, BitsightExposedAsset>,
     *               shodan: Collection<int, ShodanExposedAsset>,
     *               censys: Collection<int, CensysExposedAsset>}
     */
    private function groupByIpPort(
        Collection $bitsightAssets,
        Collection $shodanAssets,
        Collection $censysAssets
    ): array {
        $grouped = [];

        foreach ($bitsightAssets as $asset) {
            $key = "{$asset->ip}:{$asset->port}";
            $grouped[$key]['bitsight'] ??= collect();
            $grouped[$key]['bitsight']->push($asset);
        }

        foreach ($shodanAssets as $asset) {
            $key = "{$asset->ip}:{$asset->port}";
            $grouped[$key]['shodan'] ??= collect();
            $grouped[$key]['shodan']->push($asset);
        }

        foreach ($censysAssets as $asset) {
            $key = "{$asset->ip}:{$asset->port}";
            $grouped[$key]['censys'] ??= collect();
            $grouped[$key]['censys']->push($asset);
        }

        return $grouped;
    }

    /**
     * Group vendor assets by IP only (for attributions).
     *
     * @return array<string, array{bitsight: Collection<int, BitsightExposedAsset>,
     *     shodan: Collection<int, ShodanExposedAsset>,
     *     censys: Collection<int, CensysExposedAsset>}>
     */
    private function groupByIp(
        Collection $bitsightAssets,
        Collection $shodanAssets,
        Collection $censysAssets
    ): array {
        $grouped = [];

        foreach ($bitsightAssets as $asset) {
            $grouped[$asset->ip]['bitsight'] ??= collect();
            $grouped[$asset->ip]['bitsight']->push($asset);
        }

        foreach ($shodanAssets as $asset) {
            $grouped[$asset->ip]['shodan'] ??= collect();
            $grouped[$asset->ip]['shodan']->push($asset);
        }

        foreach ($censysAssets as $asset) {
            $grouped[$asset->ip]['censys'] ??= collect();
            $grouped[$asset->ip]['censys']->push($asset);
        }

        return $grouped;
    }

    /**
     * Merge detected exposure data for IP:Port from multiple vendors.
     *
     * @param array{bitsight?: Collection<int, BitsightExposedAsset>,
     *     shodan?: Collection<int, ShodanExposedAsset>,
     *     censys?: Collection<int, CensysExposedAsset>} $vendors
     */
    private function mergeExposureData(string $ip, int $port, array $vendors, ?DetectedExposure $existing): array
    {
        // Get first record from each vendor (all should have same data for this IP:Port)
        $bitsight = $vendors['bitsight']->first() ?? null;
        $shodan = $vendors['shodan']->first() ?? null;
        $censys = $vendors['censys']->first() ?? null;

        // Apply priority order: Bitsight > Shodan > Censys
        $exposureData = [
            'ip' => $ip,
            'port' => $port,
            'module' => $bitsight?->module ?? $shodan?->module ?? $censys?->module,
            'transport' => $bitsight?->transport ?? $shodan?->transport ?? $censys?->transport,
        ];

        // Handle detection timestamps
        if ($existing) {
            // UPDATE: Keep original first_detected_at, update last_detected_at to NOW
            $exposureData['first_detected_at'] = $existing->first_detected_at;
            $exposureData['last_detected_at'] = now();
        } else {
            // INSERT: Both timestamps set to NOW
            $exposureData['first_detected_at'] = now();
            $exposureData['last_detected_at'] = now();
        }

        return $exposureData;
    }

    /**
     * Merge attribution data for IP from multiple vendors with priority order: Bitsight > Shodan > Censys.
     *
     * @param array{bitsight?: Collection<int, BitsightExposedAsset>,
     *     shodan?: Collection<int, ShodanExposedAsset>,
     *     censys?: Collection<int, CensysExposedAsset>} $vendors
     */
    private function mergeAttributionData(string $ip, array $vendors, ?Attribution $existing): array
    {
        // Get first record from each vendor (all should have same data for this IP)
        $bitsight = $vendors['bitsight']->first() ?? null;
        $shodan = $vendors['shodan']->first() ?? null;
        $censys = $vendors['censys']->first() ?? null;

        // Apply priority order for single-value fields: Bitsight > Shodan > Censys
        $attributionData = [
            'ip' => $ip,
            'entity' => $bitsight?->entity ?? $shodan?->entity ?? $censys?->entity,
            'sector' => null, // Will be populated from manual source_of_attribution or accounts table
            'domain' => null, // Not available in vendor data yet
            'isp' => $bitsight?->isp ?? $shodan?->isp ?? $censys?->isp,
            'asn' => $bitsight?->asn ?? $shodan?->asn ?? $censys?->asn,
            'whois' => null, // Not available in vendor data yet
            'city' => $bitsight?->city ?? $shodan?->city ?? $censys?->city,
            'country_code' => $bitsight?->country_code ?? $shodan?->country_code ?? $censys?->country_code,
            'last_exposure_at' => now(), // Track when we last saw any exposure for this IP
        ];

        // Merge and deduplicate hostnames from all vendors
        $attributionData['hostnames'] = $this->mergeHostnames(
            $bitsight?->hostnames,
            $shodan?->hostnames,
            $censys?->hostnames
        );

        // Preserve manual source_of_attribution if it exists
        if ($existing && $existing->source_of_attribution) {
            $attributionData['source_of_attribution'] = $existing->source_of_attribution;
        }

        return $attributionData;
    }

    /**
     * Merge and deduplicate hostnames from multiple vendors.
     */
    private function mergeHostnames(?string $bitsight, ?string $shodan, ?string $censys): ?string
    {
        $allHostnames = [];

        // Split each vendor's hostnames by semicolon
        if ($bitsight) {
            $allHostnames = array_merge($allHostnames, explode(';', $bitsight));
        }

        if ($shodan) {
            $allHostnames = array_merge($allHostnames, explode(';', $shodan));
        }

        if ($censys) {
            $allHostnames = array_merge($allHostnames, explode(';', $censys));
        }

        // Remove duplicates and empty values
        $allHostnames = array_filter(array_unique($allHostnames), fn ($hostname) => !empty(trim($hostname)));

        return !empty($allHostnames) ? implode(';', $allHostnames) : null;
    }
}
