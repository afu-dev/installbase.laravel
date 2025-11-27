<?php

namespace App\Console\Commands\Temporary;

use App\Models\BitsightExposedAsset;
use Illuminate\Console\Command;

class AnalyzeDnp3Data extends Command
{
    protected $signature = 'temporary:analyze-dnp3';

    protected $description = 'Analyze dnp3 module data quality in bitsight_exposed_assets';

    private int $totalRecords = 0;

    private int $validRawDataCount = 0;

    private int $validDnp3Count = 0;

    private array $keyFrequency = [];

    public function handle(): int
    {
        $startTime = microtime(true);

        $this->info('Starting dnp3 data analysis...');
        $this->newLine();

        // Get total count for progress bar
        $this->totalRecords = BitsightExposedAsset::where('module', 'dnp3')->count();

        if ($this->totalRecords === 0) {
            $this->warn('No records found with module = "dnp3"');

            return Command::SUCCESS;
        }

        $this->info("Found {$this->totalRecords} records with module = 'dnp3'");
        $this->newLine();

        // Create progress bar
        $progressBar = $this->output->createProgressBar($this->totalRecords);
        $progressBar->start();

        // Process records in chunks
        BitsightExposedAsset::where('module', 'dnp3')
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

        // Try to find the dnp3 key (case-sensitive variants)
        $dnp3String = null;
        if (isset($decodedData['Dnp3'])) {
            $dnp3String = $decodedData['Dnp3'];
        } elseif (isset($decodedData['dnp3'])) {
            $dnp3String = $decodedData['dnp3'];
        }

        if ($dnp3String === null) {
            // No dnp3 key found
            return;
        }

        // Second decode: parse nested dnp3 JSON string
        $dnp3Data = json_decode($dnp3String, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Invalid dnp3 JSON
            return;
        }

        $this->validDnp3Count++;

        // Count key frequencies
        if (is_array($dnp3Data)) {
            foreach (array_keys($dnp3Data) as $key) {
                if (!isset($this->keyFrequency[$key])) {
                    $this->keyFrequency[$key] = 0;
                }
                $this->keyFrequency[$key]++;
            }
        }
    }

    private function displayResults(float $executionTime): void
    {
        $this->info('Dnp3 Data Analysis Results');
        $this->info(str_repeat('=', 50));
        $this->newLine();

        // Basic statistics
        $validRawDataPercentage = $this->totalRecords > 0
            ? round(($this->validRawDataCount / $this->totalRecords) * 100, 2)
            : 0;

        $validDnp3Percentage = $this->totalRecords > 0
            ? round(($this->validDnp3Count / $this->totalRecords) * 100, 2)
            : 0;

        $this->info("Total Records (module='dnp3'): {$this->totalRecords}");
        $this->info("Valid raw_data JSON: {$this->validRawDataCount} ({$validRawDataPercentage}%)");
        $this->info("Valid dnp3 nested JSON: {$this->validDnp3Count} ({$validDnp3Percentage}%)");
        $this->newLine();

        // Key frequency table
        if (!empty($this->keyFrequency)) {
            $this->info('Dnp3 Key Frequency:');

            // Sort by count descending
            arsort($this->keyFrequency);

            // Prepare table data
            $tableData = [];
            foreach ($this->keyFrequency as $key => $count) {
                $percentage = $this->validDnp3Count > 0
                    ? round(($count / $this->validDnp3Count) * 100, 2)
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
            $this->warn('No keys found in dnp3 data');
        }

        $this->newLine();
        $this->info('Execution time: '.round($executionTime, 2).' seconds');
    }
}
