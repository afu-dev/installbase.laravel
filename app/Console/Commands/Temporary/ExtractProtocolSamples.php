<?php

namespace App\Console\Commands\Temporary;

use App\Models\BitsightExposedAsset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractProtocolSamples extends Command
{
    protected $signature = 'temporary:extract-samples {protocol} {--keys=* : Comma-separated list of keys to extract}';

    protected $description = 'Extract raw_data samples for a protocol covering specified keys';

    private string $protocol;

    private array $targetKeys = [];

    private array $uncoveredKeys = [];

    private array $caseVariationsFound = [];

    private array $exportedFiles = [];

    private int $exportIndex = 1;

    private string $outputDir;

    public function handle(): int
    {
        $startTime = microtime(true);

        $this->protocol = $this->argument('protocol');

        // Parse keys option
        $this->targetKeys = $this->parseKeysOption();

        if (empty($this->targetKeys)) {
            $this->error('The --keys option is required and cannot be empty.');
            $this->newLine();
            $this->info('Example usage:');
            $this->info("  php artisan temporary:extract-samples {$this->protocol} --keys=source_address,destination_address,status");

            return Command::FAILURE;
        }

        $this->info("Extracting {$this->protocol} raw_data samples...");
        $this->newLine();

        // Initialize tracking
        $this->uncoveredKeys = $this->targetKeys;
        $this->outputDir = base_path("tests/fixtures/parsers/{$this->protocol}");

        // Create output directory if it doesn't exist
        if (! File::exists($this->outputDir)) {
            File::makeDirectory($this->outputDir, 0755, true);
            $this->info("Created directory: {$this->outputDir}");
            $this->newLine();
        }

        // Get total count
        $totalRecords = BitsightExposedAsset::where('module', $this->protocol)->count();

        if ($totalRecords === 0) {
            $this->warn("No records found with module = '{$this->protocol}'");

            return Command::SUCCESS;
        }

        $this->info("Found {$totalRecords} records with module = '{$this->protocol}'");
        $this->info('Target keys: '.implode(', ', $this->targetKeys));
        $this->newLine();

        // Process records using keyset pagination (fast for large datasets)
        $shouldStop = false;
        BitsightExposedAsset::where('module', $this->protocol)
            ->select('id', 'raw_data')
            ->chunkById(1000, function ($assets) use (&$shouldStop) {
                foreach ($assets as $asset) {
                    $this->processRecord($asset->raw_data);

                    // Check if we're done
                    if (empty($this->uncoveredKeys) && count($this->caseVariationsFound) >= 2) {
                        $shouldStop = true;

                        return false; // Break chunk processing
                    }
                }
            });

        // Calculate execution time
        $executionTime = microtime(true) - $startTime;

        // Display results
        $this->displayResults($executionTime);

        return Command::SUCCESS;
    }

    private function parseKeysOption(): array
    {
        $keysOption = $this->option('keys');

        if (empty($keysOption)) {
            return [];
        }

        // Handle both array format and comma-separated string
        if (is_array($keysOption)) {
            $keysString = implode(',', $keysOption);
        } else {
            $keysString = $keysOption;
        }

        return array_map(trim(...), explode(',', $keysString));
    }

    private function processRecord(string $rawData): void
    {
        // Decode raw_data JSON
        $decodedData = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        // Try to find the protocol key (case-sensitive variants)
        $protocolData = $this->findProtocolKey($decodedData, $this->protocol);

        if ($protocolData === null) {
            return;
        }

        $caseVariation = $protocolData['key'];

        // Decode nested protocol JSON
        $nestedData = json_decode((string) $protocolData['value'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        if (! is_array($nestedData)) {
            return;
        }

        // Check which uncovered keys exist in this record
        $keysFoundInRecord = [];
        foreach ($this->uncoveredKeys as $key) {
            if (isset($nestedData[$key])) {
                $keysFoundInRecord[] = $key;
            }
        }

        // If any uncovered keys found, export this raw_data
        if (! empty($keysFoundInRecord) || ! in_array($caseVariation, $this->caseVariationsFound)) {
            $this->exportRawData($rawData, $keysFoundInRecord, $caseVariation);

            // Mark keys as covered
            $this->uncoveredKeys = array_diff($this->uncoveredKeys, $keysFoundInRecord);

            // Track case variation
            if (! in_array($caseVariation, $this->caseVariationsFound)) {
                $this->caseVariationsFound[] = $caseVariation;
            }
        }
    }

    private function findProtocolKey(array $data, string $protocol): ?array
    {
        $variations = [
            ucfirst(strtolower($protocol)), // Dnp3, Apcupsd
            strtolower($protocol),          // dnp3, apcupsd
            strtoupper($protocol),          // DNP3, APCUPSD
            $protocol,                      // as-is
        ];

        foreach ($variations as $variation) {
            if (isset($data[$variation])) {
                return [
                    'key' => $variation,
                    'value' => $data[$variation],
                ];
            }
        }

        return null;
    }

    private function exportRawData(string $rawData, array $keysFound, string $caseVariation): void
    {
        $filename = "bitsight_{$this->protocol}_{$this->exportIndex}.json";
        $filepath = $this->outputDir.'/'.$filename;

        // Write raw_data to file (pretty-printed)
        $decoded = json_decode($rawData, true);
        $prettyJson = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        File::put($filepath, $prettyJson);

        // Track export
        $this->exportedFiles[$filename] = [
            'keys' => $keysFound,
            'case_variation' => $caseVariation,
        ];

        $this->exportIndex++;

        // Show progress
        $keysDisplay = empty($keysFound) ? "[case variation: {$caseVariation}]" : implode(', ', $keysFound);
        $this->info("Exported: {$filename} -> {$keysDisplay}");
    }

    private function displayResults(float $executionTime): void
    {
        $protocolCapitalized = ucfirst(strtolower($this->protocol));

        $this->newLine();
        $this->info("{$protocolCapitalized} Sample Extraction Results");
        $this->info(str_repeat('=', 50));
        $this->newLine();

        // Summary
        $totalExported = count($this->exportedFiles);
        $this->info("Total files exported: {$totalExported}");

        if (! empty($this->caseVariationsFound)) {
            $this->info('Case variations found: '.implode(', ', $this->caseVariationsFound));
        } else {
            $this->warn('No case variations found');
        }

        $this->newLine();

        // Files table
        if (! empty($this->exportedFiles)) {
            $this->info('Exported Files:');

            $tableData = [];
            foreach ($this->exportedFiles as $filename => $data) {
                $keysDisplay = empty($data['keys'])
                    ? "[{$data['case_variation']}]"
                    : implode(', ', $data['keys']);

                $tableData[] = [
                    $filename,
                    $keysDisplay,
                ];
            }

            $this->table(
                ['File', 'Keys Found'],
                $tableData
            );
        }

        // Uncovered keys (if any)
        if (! empty($this->uncoveredKeys)) {
            $this->newLine();
            $this->warn('Keys not found in any record:');
            foreach ($this->uncoveredKeys as $key) {
                $this->warn("  - {$key}");
            }
        }

        $this->newLine();
        $this->info('Execution time: '.round($executionTime, 2).' seconds');
    }
}
