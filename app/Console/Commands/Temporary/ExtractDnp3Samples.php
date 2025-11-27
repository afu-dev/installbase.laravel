<?php

namespace App\Console\Commands\Temporary;

use App\Models\BitsightExposedAsset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractDnp3Samples extends Command
{
    protected $signature = 'temporary:extract-dnp3-samples';

    protected $description = 'Extract raw_data samples for dnp3 protocol covering all unique keys';

    private array $targetKeys = [
        'source_address',
        'destination_address',
        'control_code',
        'status',
        'device_manufacturer',
        'device_model',
        'dnp3_conformance',
        'firmware_version',
        'hardware_version',
        'device_id_code',
        'device_location',
        'device_name',
        'serial_number',
    ];

    private array $uncoveredKeys = [];

    private array $caseVariationsFound = [];

    private array $exportedFiles = [];

    private int $exportIndex = 1;

    private string $outputDir;

    public function handle(): int
    {
        $startTime = microtime(true);

        $this->info('Extracting DNP3 raw_data samples...');
        $this->newLine();

        // Initialize tracking
        $this->uncoveredKeys = $this->targetKeys;
        $this->outputDir = base_path('tests/fixtures/parsers/dnp3');

        // Create output directory if it doesn't exist
        if (! File::exists($this->outputDir)) {
            File::makeDirectory($this->outputDir, 0755, true);
            $this->info("Created directory: {$this->outputDir}");
            $this->newLine();
        }

        // Get total count
        $totalRecords = BitsightExposedAsset::where('module', 'dnp3')->count();

        if ($totalRecords === 0) {
            $this->warn('No records found with module = "dnp3"');

            return Command::SUCCESS;
        }

        $this->info("Found {$totalRecords} records with module = 'dnp3'");
        $this->newLine();

        // Process records
        $shouldStop = false;
        BitsightExposedAsset::where('module', 'dnp3')
            ->select('id', 'raw_data')
            ->chunk(1000, function ($assets) use (&$shouldStop) {
                foreach ($assets as $asset) {
                    $this->processRecord($asset->raw_data);

                    // Check if we're done
                    if (empty($this->uncoveredKeys) && count($this->caseVariationsFound) === 2) {
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

    private function processRecord(string $rawData): void
    {
        // Decode raw_data JSON
        $decodedData = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        // Try to find the dnp3 key (case-sensitive variants)
        $dnp3String = null;
        $caseVariation = null;

        if (isset($decodedData['Dnp3'])) {
            $dnp3String = $decodedData['Dnp3'];
            $caseVariation = 'Dnp3';
        } elseif (isset($decodedData['dnp3'])) {
            $dnp3String = $decodedData['dnp3'];
            $caseVariation = 'dnp3';
        }

        if ($dnp3String === null) {
            return;
        }

        // Decode nested dnp3 JSON
        $dnp3Data = json_decode($dnp3String, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        if (! is_array($dnp3Data)) {
            return;
        }

        // Check which uncovered keys exist in this record
        $keysFoundInRecord = [];
        foreach ($this->uncoveredKeys as $key) {
            if (isset($dnp3Data[$key])) {
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

    private function exportRawData(string $rawData, array $keysFound, string $caseVariation): void
    {
        $filename = "bitsight_dnp3_{$this->exportIndex}.json";
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
        $this->newLine();
        $this->info('DNP3 Sample Extraction Results');
        $this->info(str_repeat('=', 50));
        $this->newLine();

        // Summary
        $totalExported = count($this->exportedFiles);
        $this->info("Total files exported: {$totalExported}");
        $this->info('Case variations found: '.implode(', ', $this->caseVariationsFound));
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
