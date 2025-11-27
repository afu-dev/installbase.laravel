<?php

namespace App\Console\Commands\Temporary;

use App\Models\BitsightExposedAsset;
use Illuminate\Console\Command;

class DisplayFieldValues extends Command
{
    protected $signature = 'temporary:display-field {protocol} {field}';

    protected $description = 'Display unique values and counts for a specific field in a protocol\'s nested JSON data';

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
        $this->totalRecords = BitsightExposedAsset::where('module', $protocol)->count();

        if ($this->totalRecords === 0) {
            $this->warn("No records found with module = '{$protocol}'");

            return Command::SUCCESS;
        }

        $this->info("Found {$this->totalRecords} records with module = '{$protocol}'");
        $this->newLine();

        // Create progress bar
        $progressBar = $this->output->createProgressBar($this->totalRecords);
        $progressBar->start();

        // Process records in chunks
        BitsightExposedAsset::where('module', $protocol)
            ->select('id', 'raw_data')
            ->chunk(1000, function ($assets) use ($progressBar, $protocol, $field) {
                foreach ($assets as $asset) {
                    $this->extractFieldValue($asset->raw_data, $protocol, $field);
                    $progressBar->advance();
                }
            });

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
        // First decode: parse raw_data JSON
        $decodedData = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        // Try to find the protocol key (case-insensitive variants)
        $protocolString = null;
        $protocolCapitalized = ucfirst(strtolower($protocol));

        if (isset($decodedData[$protocolCapitalized])) {
            $protocolString = $decodedData[$protocolCapitalized];
        } elseif (isset($decodedData[strtolower($protocol)])) {
            $protocolString = $decodedData[strtolower($protocol)];
        } elseif (isset($decodedData[strtoupper($protocol)])) {
            $protocolString = $decodedData[strtoupper($protocol)];
        }

        if ($protocolString === null) {
            return;
        }

        // Second decode: parse nested protocol JSON string
        $protocolData = json_decode($protocolString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        // Check if field exists
        if (!isset($protocolData[$field])) {
            return;
        }

        $this->recordsWithField++;

        // Collect field value (convert to string for consistency)
        $value = $protocolData[$field];
        if (is_array($value)) {
            $value = json_encode($value);
        } elseif (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_null($value)) {
            $value = 'null';
        } else {
            $value = (string) $value;
        }

        $this->fieldValues[] = $value;
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
                $displayValue = strlen($value) > 80 ? substr($value, 0, 77).'...' : $value;

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
        $this->info('Execution time: '.round($executionTime, 2).' seconds');
    }
}
