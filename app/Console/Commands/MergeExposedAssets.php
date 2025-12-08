<?php

namespace App\Console\Commands;

use App\Enums\Vendor;
use App\Models\BitsightExposedAsset;
use App\Models\DetectedExposure;
use App\Models\Scan;
use App\Models\ShodanExposedAsset;
use App\Services\Parsers\DataParserFactory;
use App\Services\Parsers\ParsedDeviceData;
use Exception;
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
            $this->error("Scan has $unfinishedCount unfinished execution(s).");

            return Command::FAILURE;
        }

        $this->info("Processing {$executions->count()} execution(s)");

        $executionIdsRanges = $this->groupIntoRanges($executions->pluck('id')->toArray());

        // Process each vendor sequentially
        $this->processVendorRecords(Vendor::BITSIGHT, $executionIdsRanges);
        $this->processVendorRecords(Vendor::SHODAN, $executionIdsRanges);

        $executionTime = microtime(true) - $startTime;
        $this->info("Execution time: " . round($executionTime, 2) . " seconds");

        return Command::SUCCESS;
    }

    private function processVendorRecords(Vendor $vendor, array $executionIdsRanges): void
    {
        $this->newLine();
        $this->info("--- Processing {$vendor->value} ---");

        // Get model class
        $modelClass = match ($vendor) {
            Vendor::BITSIGHT => BitsightExposedAsset::class,
            Vendor::SHODAN => ShodanExposedAsset::class,
        };

        // Build query with execution_id filters
        $query = $modelClass::query();
        $query->where(function (Builder $q) use ($executionIdsRanges): void {
            foreach ($executionIdsRanges as $range) {
                if ($range["start"] === $range["end"]) {
                    $q->orWhere("execution_id", $range["start"]);
                } else {
                    $q->orWhereBetween("execution_id", [$range["start"], $range["end"]]);
                }
            }
        });

        // Count for progress bar
        $total = $query->count();
        $this->info("Processing $total records");

        // Progress tracking
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->setFormat(ProgressBar::FORMAT_VERBOSE);

        $parseFailures = 0;
        $factory = app(DataParserFactory::class);

        // CRITICAL: Use lazyById for keyset pagination
        foreach ($query->lazyById(1000, 'id') as $record) {
            $progressBar->setMessage("Processing {$record->ip}:{$record->port}");

            try {
                // Parse raw data
                $parser = $factory->make($vendor->value, $record->module ?? 'other');
                $devices = $parser->parse($record->raw_data);

                // Create/update exposure for each parsed device
                if (empty($devices)) {
                    // Empty result, create exposure without device data
                    $this->upsertDetectedExposure($vendor, $record, null);
                } else {
                    foreach ($devices as $device) {
                        $this->upsertDetectedExposure($vendor, $record, $device);
                    }
                }
            } catch (Exception $e) {
                dd($e);
                // Parse failed, create exposure without device data
                $parseFailures++;
                $this->upsertDetectedExposure($vendor, $record, null);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("Completed: $total records processed, $parseFailures parse failures");
    }

    private function upsertDetectedExposure(
        Vendor                                  $vendor,
        BitsightExposedAsset|ShodanExposedAsset $record,
        ?ParsedDeviceData                       $device
    ): void
    {
        // Find existing exposure
        $existing = DetectedExposure::forIpPort($record->ip, $record->port)->first();

        // Calculate dates (MIN for first, MAX for last)
        $firstDetectedAt = $existing
            ? min($existing->first_detected_at, $record->detected_at)
            : $record->detected_at;

        $lastDetectedAt = $existing
            ? max($existing->last_detected_at, $record->detected_at)
            : $record->detected_at;

        // Build data array
        $data = [
            'ip' => $record->ip,
            'port' => $record->port,
            'source' => $vendor->value,
            'transport' => $record->transport,
            'module' => $record->module,
            'first_detected_at' => $firstDetectedAt,
            'last_detected_at' => $lastDetectedAt,
        ];

        // Add device fields if parsing succeeded
        if ($device) {
            $deviceData = [
                'vendor' => $device->vendor,
                'fingerprint' => $device->fingerprint,
                'version' => $device->version,
                'sn' => $device->sn,
                'device_mac' => $device->device_mac,
                'modbus_project_info' => $device->modbus_project_info,
                'opc-ua_security_policy' => $device->opc_ua_security_policy,
                'is_guest_account_active' => $device->is_guest_account_active,
                'registration_info' => $device->registration_info,
                'secure_power_app' => $device->secure_power_app,
                'nmc_card_number' => $device->nmc_card_num,
                'fingerprint_raw' => $device->fingerprint_raw,
            ];
        } else {
            $deviceData = [
                "vendor" => "not_parsed",
            ];
        }

        $data = array_merge($data, $deviceData);

        // Filter nulls to preserve existing data
        $data = array_filter($data, fn($value) => $value !== null);

        // Upsert
        DetectedExposure::updateOrCreate(
            ['ip' => $record->ip, 'port' => $record->port],
            $data
        );
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
}
