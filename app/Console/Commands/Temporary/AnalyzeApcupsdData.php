<?php

namespace App\Console\Commands\Temporary;

use App\Models\BitsightExposedAsset;
use Illuminate\Console\Command;

class AnalyzeApcupsdData extends Command
{
    protected $signature = 'temporary:analyze-apcupsd';

    protected $description = 'Analyze apcupsd module data quality in bitsight_exposed_assets';

    private int $totalRecords = 0;

    private int $validRawDataCount = 0;

    private int $validApcupsdCount = 0;

    private array $keyFrequency = [];

    public function handle(): int
    {
        $startTime = microtime(true);

        $this->info('Starting apcupsd data analysis...');
        $this->newLine();

        // Get total count for progress bar
        $this->totalRecords = BitsightExposedAsset::where('module', 'apcupsd')->count();

        if ($this->totalRecords === 0) {
            $this->warn('No records found with module = "apcupsd"');

            return Command::SUCCESS;
        }

        $this->info("Found {$this->totalRecords} records with module = 'apcupsd'");
        $this->newLine();

        // Create progress bar
        $progressBar = $this->output->createProgressBar($this->totalRecords);
        $progressBar->start();

        // Process records in chunks
        BitsightExposedAsset::where('module', 'apcupsd')
            ->select('id', 'raw_data')
            ->chunk(1000, function ($assets) use ($progressBar) {
                foreach ($assets as $asset) {
                    $this->analyzeRecord($asset->raw_data);
                    $progressBar->advance();
                }
            });

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

        // Try to find the apcupsd key (case-sensitive variants)
        $apcupsdString = null;
        if (isset($decodedData['Apcupsd'])) {
            $apcupsdString = $decodedData['Apcupsd'];
        } elseif (isset($decodedData['apcupsd'])) {
            $apcupsdString = $decodedData['apcupsd'];
        }

        if ($apcupsdString === null) {
            // No apcupsd key found
            return;
        }

        // Second decode: parse nested apcupsd JSON string
        $apcupsdData = json_decode($apcupsdString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Invalid apcupsd JSON
            return;
        }

        $this->validApcupsdCount++;

        // Count key frequencies
        if (is_array($apcupsdData)) {
            foreach (array_keys($apcupsdData) as $key) {
                if (!isset($this->keyFrequency[$key])) {
                    $this->keyFrequency[$key] = 0;
                }
                $this->keyFrequency[$key]++;
            }
        }
    }

    private function displayResults(float $executionTime): void
    {
        $this->info('Apcupsd Data Analysis Results');
        $this->info(str_repeat('=', 50));
        $this->newLine();

        // Basic statistics
        $validRawDataPercentage = $this->totalRecords > 0
            ? round(($this->validRawDataCount / $this->totalRecords) * 100, 2)
            : 0;

        $validApcupsdPercentage = $this->totalRecords > 0
            ? round(($this->validApcupsdCount / $this->totalRecords) * 100, 2)
            : 0;

        $this->info("Total Records (module='apcupsd'): {$this->totalRecords}");
        $this->info("Valid raw_data JSON: {$this->validRawDataCount} ({$validRawDataPercentage}%)");
        $this->info("Valid apcupsd nested JSON: {$this->validApcupsdCount} ({$validApcupsdPercentage}%)");
        $this->newLine();

        // Key frequency table
        if (!empty($this->keyFrequency)) {
            $this->info('Apcupsd Key Frequency:');

            // Sort by count descending
            arsort($this->keyFrequency);

            // Prepare table data
            $tableData = [];
            foreach ($this->keyFrequency as $key => $count) {
                $percentage = $this->validApcupsdCount > 0
                    ? round(($count / $this->validApcupsdCount) * 100, 2)
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
            $this->warn('No keys found in apcupsd data');
        }

        $this->newLine();
        $this->info('Execution time: '.round($executionTime, 2).' seconds');
    }
}
