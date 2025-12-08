<?php

namespace App\Console\Commands\Temporary;

use App\Models\ShodanExposedAsset;
use Illuminate\Console\Command;

class DisplayShodanFieldValues extends Command
{
    protected $signature = 'temporary:display-shodan-field {protocol} {field}';

    protected $description = 'Display unique values and counts for a specific field in a protocol\'s shodan data';

    private array $fieldValues = [];

    private int $totalRecords = 0;

    private int $recordsWithField = 0;

    public function handle(): int
    {
        $startTime = microtime(true);

        $protocol = $this->argument('protocol');
        $field = $this->argument('field');

        $this->info("Analyzing field '{$field}' in protocol '{$protocol}'...");
        $this->newLine();

        // Get total count for progress bar
        $this->totalRecords = ShodanExposedAsset::where('module', $protocol)->count();

        if ($this->totalRecords === 0) {
            $this->warn("No records found with module = '{$protocol}'");

            return Command::SUCCESS;
        }

        $this->info("Found {$this->totalRecords} records with module = '{$protocol}'");
        $this->newLine();

        // Create progress bar
        $progressBar = $this->output->createProgressBar($this->totalRecords);
        $progressBar->start();

        // Process records using keyset pagination (fast for large datasets)
        $assets = ShodanExposedAsset::where('module', $protocol)
            ->select('id', 'raw_data')
            ->lazyById(1000);

        foreach ($assets as $asset) {
            $this->extractFieldValue($asset->raw_data, $protocol, $field);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Calculate execution time
        $executionTime = microtime(true) - $startTime;

        // Display results
        $this->displayResults($protocol, $field, $executionTime);

        return Command::SUCCESS;
    }

    private function extractFieldValue(string $rawData, string $protocol, string $field): void
    {
        match ($protocol) {
            "apcupsd" => $this->extractApcupsdFieldValue($rawData, $field),
        };
    }

    private function extractApcupsdFieldValue(string $rawData, string $field): void
    {
        $apcuData = [];
        $lines = explode("\n", trim($rawData));
        foreach ($lines as $line) {
            [$key, $value] = explode(':', $line, 2);
            $apcuData[trim($key)] = trim($value);
        }

        // Check if field exists
        if (!isset($apcuData[$field])) {
            return;
        }

        $this->recordsWithField++;
        $this->fieldValues[] = $apcuData[$field];
    }

    private function displayResults(string $protocol, string $field, float $executionTime): void
    {
        $this->info("Field Analysis Results: {$protocol}.{$field}");
        $this->info(str_repeat('=', 50));
        $this->newLine();

        // Basic statistics
        $percentage = $this->totalRecords > 0
            ? round(($this->recordsWithField / $this->totalRecords) * 100, 2)
            : 0;

        $this->info("Total Records (module='{$protocol}'): {$this->totalRecords}");
        $this->info("Records with field '{$field}': {$this->recordsWithField} ({$percentage}%)");
        $this->newLine();

        // Count unique values
        if (!empty($this->fieldValues)) {
            $valueCounts = array_count_values($this->fieldValues);
            arsort($valueCounts); // Sort by count descending

            $uniqueCount = count($valueCounts);
            $this->info("Unique values: {$uniqueCount}");
            $this->newLine();

            // Prepare table data
            $tableData = [];
            foreach ($valueCounts as $value => $count) {
                $valuePercentage = $this->recordsWithField > 0
                    ? round(($count / $this->recordsWithField) * 100, 2)
                    : 0;

                // Truncate long values for display
                $displayValue = strlen((string) $value) > 80 ? substr((string) $value, 0, 77) . '...' : $value;

                $tableData[] = [
                    $displayValue,
                    number_format($count),
                    "{$valuePercentage}%",
                ];
            }

            $this->table(
                ['Value', 'Count', 'Percentage'],
                $tableData
            );
        } else {
            $this->warn("No values found for field '{$field}'");
        }

        $this->newLine();
        $this->info('Execution time: ' . round($executionTime, 2) . ' seconds');
    }
}
