<?php

namespace App\Console\Commands;

use App\Enums\Vendor;
use App\Models\Attribution;
use App\Models\BitsightExposedAsset;
use App\Models\CensysExposedAsset;
use App\Models\DetectedExposure;
use App\Models\Scan;
use App\Models\ShodanExposedAsset;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Console\Helper\ProgressBar;

class MergeExposedAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "exposed-assets:merge {--scan-id= : Specific scan ID to merge}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Merge vendor exposed assets into detected_exposures and attributions tables";

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $startTime = microtime(true);

        $scan = $this->getScan();
        if (!$scan) {
            $this->error("No scan found.");

            return Command::FAILURE;
        }

        $this->info("Processing scan ID: $scan->id");

        $executions = $scan->executions;
        $unfinishedCount = $executions->whereNull("finished_at")->count();

        if ($unfinishedCount > 0) {
            $this->error("Scan has $unfinishedCount unfinished execution(s). Cannot merge until all executions complete.");

            return Command::FAILURE;
        }

        $this->info("Processing {$executions->count()} execution(s)");

        $executionIdsRanges = $this->groupIntoRanges($executions->pluck('id')->toArray());

        $this->processExecutions($executionIdsRanges);

        return Command::SUCCESS;
    }

    private function processExecutions(array $executionIdsRanges): void
    {
        $ips = array_unique(array_merge(
            $this->processVendorExecutions(Vendor::SHODAN, ShodanExposedAsset::class, $executionIdsRanges),
            $this->processVendorExecutions(Vendor::CENSYS, CensysExposedAsset::class, $executionIdsRanges),
            $this->processVendorExecutions(Vendor::BITSIGHT, BitsightExposedAsset::class, $executionIdsRanges),
        ));

        $finalCount = count($ips);

        $progressBar = $this->output->createProgressBar($finalCount);
        $progressBar->setFormat(ProgressBar::FORMAT_VERBOSE);

        foreach ($ips as $ip) {
            $progressBar->setMessage("Processing IP: $ip");

            $vendorsData = $this->getVendorsData($ip, $executionIdsRanges);

            $exposuresData = $this->mergeVendorData($vendorsData);
            $attributionData = $this->mergeAttributionData($exposuresData);

            // Upsert Attribution (one record per IP)
            // Filter out null values to preserve existing data
            $attributionUpsertData = array_filter($attributionData, fn ($value) => $value !== null);

            Attribution::updateOrCreate(
                ['ip' => $ip],
                $attributionUpsertData
            );

            // Upsert DetectedExposures (one per IP:Port)
            foreach ($exposuresData as $exposure) {
                // Find existing record or create new
                $existingExposure = DetectedExposure::forIpPort($exposure['ip'], $exposure['port'])->first();

                // Calculate proper dates (MIN for first, MAX for last)
                $firstDetectedAt = $exposure['first_detected_at'];
                $lastDetectedAt = $exposure['last_detected_at'];

                if ($existingExposure) {
                    // Use MIN for first_detected_at
                    if ($existingExposure->first_detected_at && $firstDetectedAt) {
                        $firstDetectedAt = min($existingExposure->first_detected_at, $firstDetectedAt);
                    } elseif ($existingExposure->first_detected_at) {
                        $firstDetectedAt = $existingExposure->first_detected_at;
                    }

                    // Use MAX for last_detected_at
                    if ($existingExposure->last_detected_at && $lastDetectedAt) {
                        $lastDetectedAt = max($existingExposure->last_detected_at, $lastDetectedAt);
                    } elseif ($existingExposure->last_detected_at) {
                        $lastDetectedAt = $existingExposure->last_detected_at;
                    }
                }

                // Prepare upsert data with calculated dates
                $exposureUpsertData = array_filter(
                    $exposure,
                    fn ($value, $key) => !str_starts_with($key, '_') && $value !== null,
                    ARRAY_FILTER_USE_BOTH
                );

                // Override dates with calculated values
                $exposureUpsertData['first_detected_at'] = $firstDetectedAt;
                $exposureUpsertData['last_detected_at'] = $lastDetectedAt;

                DetectedExposure::updateOrCreate(
                    ['ip' => $exposure['ip'], 'port' => $exposure['port']],
                    $exposureUpsertData
                );
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
    }

    /**
     * @param Vendor $vendor
     * @param class-string<BitsightExposedAsset|ShodanExposedAsset|CensysExposedAsset> $modelClass
     * @param array $executionIdsRanges
     * @return array
     */
    private function processVendorExecutions(Vendor $vendor, string $modelClass, array $executionIdsRanges): array
    {
        $this->newLine();
        $this->info("--- Processing {$vendor->value} ---");

        $ipQuery = \DB::table((new $modelClass())->getTable())->select("ip");

        foreach ($executionIdsRanges as $executionIdRange) {
            if ($executionIdRange["start"] === $executionIdRange["end"]) {
                $ipQuery->orWhere("execution_id", $executionIdRange["start"]);
            } else {
                $ipQuery->orWhereBetween("execution_id", [$executionIdRange["start"], $executionIdRange["end"]]);
            }
        }

        $ips = $ipQuery->get()
            ->pluck("ip")
            ->toArray();

        $ipsCount = count($ips);
        $this->info("Got $ipsCount ips");

        return $ips;
    }

    /**
     * Get scan from option or latest scan.
     */
    private function getScan(): ?Scan
    {
        if ($scanId = $this->option("scan-id")) {
            return Scan::find($scanId);
        }

        return Scan::latest()->first();
    }

    private function groupIntoRanges(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $ids = array_unique($ids);
        sort($ids);

        $ranges = [];
        $rangeStart = $ids[0];
        $rangeEnd = $ids[0];

        for ($i = 1; $i < count($ids); $i++) {
            if ($ids[$i] === $rangeEnd + 1) {
                // if consecutive, extend
                $rangeEnd = $ids[$i];
            } else {
                // else it's a gap, save and start new one
                $ranges[] = ["start" => $rangeStart, "end" => $rangeEnd];
                $rangeStart = $ids[$i];
                $rangeEnd = $ids[$i];
            }
        }

        $ranges[] = ['start' => $rangeStart, 'end' => $rangeEnd];

        return $ranges;
    }

    /**
     * Merge vendor data for a single IP across all ports.
     * Returns array of records, one per unique (ip, port) combination.
     *
     * Priority order: Bitsight > Shodan > Censys
     * For each field, use first non-null value following priority order.
     *
     * @param array $vendorsData Array with keys 'bitsight', 'shodan', 'censys', values are Collections
     * @return array Array of arrays, each containing merged data for one (ip, port) combo
     */
    private function mergeVendorData(array $vendorsData): array
    {
        // Step 1: Extract all unique ports from all vendors
        $allPorts = [];

        foreach ($vendorsData as $vendorKey => $collection) {
            foreach ($collection as $record) {
                $allPorts[] = $record->port;
            }
        }

        $uniquePorts = array_unique($allPorts);

        // Step 2: For each port, merge data from all vendors
        $mergedRecords = [];

        foreach ($uniquePorts as $port) {
            // Get first record from each vendor for this port (or null if vendor has no records)
            $bitsight = $vendorsData['bitsight']->where('port', $port)->first();
            $shodan = $vendorsData['shodan']->where('port', $port)->first();
            $censys = $vendorsData['censys']->where('port', $port)->first();

            // Determine source vendor (first vendor that has this port)
            $source = null;
            if ($bitsight) {
                $source = 'bitsight';
            } elseif ($shodan) {
                $source = 'shodan';
            } elseif ($censys) {
                $source = 'censys';
            }

            // Skip if no vendor has this port (shouldn't happen)
            if (!$source) {
                continue;
            }

            // Get IP from first available record
            $ip = $bitsight->ip ?? $shodan->ip ?? $censys->ip;

            // Step 3: Apply null coalescing for each field (Bitsight > Shodan > Censys)
            $transport = $bitsight->transport ?? $shodan->transport ?? $censys->transport ?? null;
            $module = $bitsight->module ?? $shodan->module ?? $censys->module ?? null;

            // Step 4: Merge hostnames from all vendors for this port
            $allHostnames = [];

            foreach ([$bitsight, $shodan, $censys] as $record) {
                if ($record && !empty($record->hostnames)) {
                    // Split by comma or semicolon (in case vendors use different separators)
                    $hostnames = preg_split('/[,;]\s*/', $record->hostnames);
                    $allHostnames = array_merge($allHostnames, $hostnames);
                }
            }

            // Sort, unique, and implode hostnames
            $allHostnames = array_filter($allHostnames); // Remove empty values
            $allHostnames = array_unique($allHostnames);
            sort($allHostnames);
            $mergedHostnames = implode(',', $allHostnames);

            // Step 5: Calculate first_detected_at (MIN) and last_detected_at (MAX)
            $allDates = [];

            foreach ([$bitsight, $shodan, $censys] as $record) {
                if ($record && $record->detected_at) {
                    $allDates[] = $record->detected_at;
                }
            }

            $firstDetectedAt = !empty($allDates) ? min($allDates) : null;
            $lastDetectedAt = !empty($allDates) ? max($allDates) : null;

            // Step 6: Apply null coalescing for attribution fields (for later use)
            $entity = $bitsight->entity ?? $shodan->entity ?? $censys->entity ?? null;
            $isp = $bitsight->isp ?? $shodan->isp ?? $censys->isp ?? null;
            $country_code = $bitsight->country_code ?? $shodan->country_code ?? $censys->country_code ?? null;
            $city = $bitsight->city ?? $shodan->city ?? $censys->city ?? null;
            $asn = $bitsight->asn ?? $shodan->asn ?? $censys->asn ?? null;
            $os = $bitsight->os ?? $shodan->os ?? $censys->os ?? null;
            $product = $bitsight->product ?? $shodan->product ?? $censys->product ?? null;
            $product_sn = $bitsight->product_sn ?? $shodan->product_sn ?? $censys->product_sn ?? null;
            $version = $bitsight->version ?? $shodan->version ?? $censys->version ?? null;

            // Step 7: Build merged record
            $mergedRecords[] = [
                // DetectedExposure fields
                'ip' => $ip,
                'port' => $port,
                'source' => $source,
                'transport' => $transport,
                'module' => $module,
                'first_detected_at' => $firstDetectedAt,
                'last_detected_at' => $lastDetectedAt,

                // Attribution fields (prefixed with _ for now, will be used in attribution merge)
                '_hostnames' => $mergedHostnames,
                '_entity' => $entity,
                '_isp' => $isp,
                '_country_code' => $country_code,
                '_city' => $city,
                '_asn' => $asn,
                '_os' => $os,
                '_product' => $product,
                '_product_sn' => $product_sn,
                '_version' => $version,
            ];
        }

        return $mergedRecords;
    }

    /**
     * Merge attribution data from exposure records.
     * Creates a single attribution record per IP by aggregating data from all ports.
     *
     * Priority order: Bitsight > Shodan > Censys
     * For each field, use first non-null value from exposures following priority order.
     *
     * @param array $exposuresData Array of exposure records (one per IP:Port)
     * @return array Attribution data for the IP
     */
    private function mergeAttributionData(array $exposuresData): array
    {
        if (empty($exposuresData)) {
            return [];
        }

        // Get IP from first exposure (all have same IP)
        $ip = $exposuresData[0]['ip'];

        // Step 1: Sort exposures by priority (bitsight first, then shodan, then censys)
        $priorityOrder = ['bitsight' => 1, 'shodan' => 2, 'censys' => 3];
        usort($exposuresData, fn ($a, $b) => ($priorityOrder[$a['source']] ?? 999) <=> ($priorityOrder[$b['source']] ?? 999));

        // Step 2: Apply null coalescing for each attribution field
        $entity = null;
        $isp = null;
        $asn = null;
        $city = null;
        $country_code = null;

        foreach ($exposuresData as $exposure) {
            $entity ??= $exposure['_entity'];
            $isp ??= $exposure['_isp'];
            $asn ??= $exposure['_asn'];
            $city ??= $exposure['_city'];
            $country_code ??= $exposure['_country_code'];
        }

        // Step 3: Merge hostnames from all ports
        $allHostnames = [];

        foreach ($exposuresData as $exposure) {
            if (!empty($exposure['_hostnames'])) {
                $hostnames = explode(',', $exposure['_hostnames']);
                $allHostnames = array_merge($allHostnames, $hostnames);
            }
        }

        // Sort, unique, and implode hostnames
        $allHostnames = array_filter($allHostnames); // Remove empty values
        $allHostnames = array_unique($allHostnames);
        sort($allHostnames);
        $mergedHostnames = implode(',', $allHostnames);

        // Step 4: Calculate last_exposure_at as MAX of all last_detected_at
        $allLastDates = [];

        foreach ($exposuresData as $exposure) {
            if (!empty($exposure['last_detected_at'])) {
                $allLastDates[] = $exposure['last_detected_at'];
            }
        }

        $lastExposureAt = !empty($allLastDates) ? max($allLastDates) : null;

        // Step 5: Build attribution data
        return [
            'ip' => $ip,
            'entity' => $entity,
            // 'sector' => null, // Manual field, not populated from vendors
            // 'domain' => null, // Manual field, not populated from vendors
            'hostnames' => $mergedHostnames,
            'isp' => $isp,
            'asn' => $asn,
            // 'whois' => null, // Manual field, not populated from vendors
            'city' => $city,
            'country_code' => $country_code,
            // 'source_of_attribution' => null, // Manual field
            'last_exposure_at' => $lastExposureAt,
        ];
    }

    /**
     * @param mixed $ip
     * @param array $executionIdsRanges
     * @return array
     */
    private function getVendorsData(mixed $ip, array $executionIdsRanges): array
    {
        $vendorsData = [];
        foreach (Vendor::cases() as $vendor) {
            $modelClass = match ($vendor) {
                Vendor::CENSYS => CensysExposedAsset::class,
                Vendor::SHODAN => ShodanExposedAsset::class,
                Vendor::BITSIGHT => BitsightExposedAsset::class,
            };
            $query = $modelClass::where("ip", $ip);
            $query->where(function (Builder $query) use ($executionIdsRanges) {
                foreach ($executionIdsRanges as $executionIdRange) {
                    if ($executionIdRange["start"] === $executionIdRange["end"]) {
                        $query->orWhere("execution_id", $executionIdRange["start"]);
                    } else {
                        $query->orWhereBetween("execution_id", [$executionIdRange["start"], $executionIdRange["end"]]);
                    }
                }
            });
            $vendorsData[$vendor->value] = $query->get();
        }
        return $vendorsData;
    }
}
