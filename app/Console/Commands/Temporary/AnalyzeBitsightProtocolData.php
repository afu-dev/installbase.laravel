<?php

namespace App\Console\Commands\Temporary;

use App\Models\BitsightExposedAsset;
use Illuminate\Console\Command;

class AnalyzeBitsightProtocolData extends Command
{
    protected $signature = 'temporary:analyze-bitsight-protocol {protocol}';

    protected $description = 'Analyze protocol module data quality in bitsight_exposed_assets';

    private string $protocol;

    private int $totalRecords = 0;

    private int $validRawDataCount = 0;

    private int $validProtocolCount = 0;

    private array $keyFrequency = [];

    public function handle(): int
    {
        $startTime = microtime(true);

        $this->protocol = $this->argument('protocol');

        $this->info("Starting {$this->protocol} data analysis...");
        $this->newLine();

        // Get total count for progress bar
        $this->totalRecords = BitsightExposedAsset::where('module', $this->protocol)->count();

        if ($this->totalRecords === 0) {
            $this->warn("No records found with module = '{$this->protocol}'");

            return Command::SUCCESS;
        }

        $this->info("Found {$this->totalRecords} records with module = '{$this->protocol}'");
        $this->newLine();

        // Create progress bar
        $progressBar = $this->output->createProgressBar($this->totalRecords);
        $progressBar->start();

        // Process records using keyset pagination (fast for large datasets)
        $assets = BitsightExposedAsset::where('module', $this->protocol)
            ->select('id', 'raw_data')
            ->lazyById(1000);

        foreach ($assets as $asset) {
            $this->analyzeRecord($asset->raw_data);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Calculate execution time
        $executionTime = microtime(true) - $startTime;

        // Display results
        $this->displayResults($executionTime);

        return Command::SUCCESS;
    }

    private function analyzeRecord(string $rawData): void
    {
        // First decode: parse raw_data JSON
        $decodedData = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Invalid raw_data JSON
            return;
        }

        $this->validRawDataCount++;

        // Try to find the protocol key (case-sensitive variants)
        $protocolData = $this->findProtocolKey($decodedData, $this->protocol);

        if ($protocolData === null) {
            // No protocol key found
            return;
        }

        // Second decode: parse nested protocol JSON string
        $nestedData = json_decode($protocolData['value'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Invalid protocol JSON
            return;
        }

        $this->validProtocolCount++;

        // Count key frequencies
        if (is_array($nestedData)) {
            foreach (array_keys($nestedData) as $key) {
                if (! isset($this->keyFrequency[$key])) {
                    $this->keyFrequency[$key] = 0;
                }
                $this->keyFrequency[$key]++;
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

    private function displayResults(float $executionTime): void
    {
        $protocolCapitalized = ucfirst(strtolower($this->protocol));

        $this->info("{$protocolCapitalized} Data Analysis Results");
        $this->info(str_repeat('=', 50));
        $this->newLine();

        // Basic statistics
        $validRawDataPercentage = $this->totalRecords > 0
            ? round(($this->validRawDataCount / $this->totalRecords) * 100, 2)
            : 0;

        $validProtocolPercentage = $this->totalRecords > 0
            ? round(($this->validProtocolCount / $this->totalRecords) * 100, 2)
            : 0;

        $this->info("Total Records (module='{$this->protocol}'): {$this->totalRecords}");
        $this->info("Valid raw_data JSON: {$this->validRawDataCount} ({$validRawDataPercentage}%)");
        $this->info("Valid {$this->protocol} nested JSON: {$this->validProtocolCount} ({$validProtocolPercentage}%)");
        $this->newLine();

        // Key frequency table
        if (! empty($this->keyFrequency)) {
            $this->info("{$protocolCapitalized} Key Frequency:");

            // Sort by count descending
            arsort($this->keyFrequency);

            // Prepare table data
            $tableData = [];
            foreach ($this->keyFrequency as $key => $count) {
                $percentage = $this->validProtocolCount > 0
                    ? round(($count / $this->validProtocolCount) * 100, 2)
                    : 0;

                $tableData[] = [
                    $key,
                    number_format($count),
                    "{$percentage}%",
                ];
            }

            $this->table(
                ['Key', 'Count', 'Percentage'],
                $tableData
            );
        } else {
            $this->warn("No keys found in {$this->protocol} data");
        }

        $this->newLine();
        $this->info('Execution time: '.round($executionTime, 2).' seconds');
    }
}
