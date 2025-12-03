<?php

namespace App\Console\Commands\Temporary;

use App\Models\BitsightExposedAsset;
use App\Models\ShodanExposedAsset;
use Illuminate\Console\Command;

class AnalyzeShodanProtocolData extends Command
{
    protected $signature = 'temporary:analyze-shodan-protocol {protocol}';

    protected $description = 'Analyze protocol module data quality in shodan_exposed_assets';

    private string $protocol;

    private int $totalRecords = 0;

    private array $keyFrequency = [];

    public function handle(): int
    {
        $startTime = microtime(true);

        $this->protocol = $this->argument('protocol');

        $this->info("Starting {$this->protocol} data analysis...");
        $this->newLine();

        // Get total count for progress bar
        $this->totalRecords = ShodanExposedAsset::where('module', $this->protocol)->count();

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
        $assets = ShodanExposedAsset::where('module', $this->protocol)
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
        // done in alphabetical order, if multiple protocol have the same construction, it goes into the first one.
        match ($this->protocol) {
            "apcupsd" => $this->analyzeApcupsd($rawData),
            "bacnet", "ethernetip", "iec-61850" => $this->analyzeBacnet($rawData),
        };
    }

    private function analyzeApcupsd(string $rawData): void
    {
        $apcuKeys = [];
        $lines = explode("\n", trim($rawData));
        foreach ($lines as $line) {
            [$key,] = explode(':', $line, 2);
            $apcuKeys[] = trim($key);
        }

        $this->addKeys($apcuKeys);
    }

    private function analyzeBacnet(string $rawData): void
    {
        $bacnetKeys = [];
        $lines = explode("\n", trim($rawData));
        foreach ($lines as $line) {
            [$key,] = explode(':', $line, 2);
            $bacnetKeys[] = trim($key);
        }

        $this->addKeys($bacnetKeys);
    }

    private function addKeys(array $keys): void
    {
        foreach ($keys as $key) {
            if (!isset($this->keyFrequency[$key])) {
                $this->keyFrequency[$key] = 0;
            }
            $this->keyFrequency[$key]++;
        }
    }

    private function displayResults(float $executionTime): void
    {
        $protocolCapitalized = ucfirst(strtolower($this->protocol));

        $this->info("{$protocolCapitalized} Data Analysis Results");
        $this->info(str_repeat('=', 50));
        $this->newLine();


        $this->info("Total Records (module='{$this->protocol}'): {$this->totalRecords}");
        $this->newLine();

        // Key frequency table
        if (!empty($this->keyFrequency)) {
            $this->info("{$protocolCapitalized} Key Frequency:");

            // Sort by count descending
            arsort($this->keyFrequency);

            // Prepare table data
            $tableData = [];
            foreach ($this->keyFrequency as $key => $count) {
                $percentage = $this->totalRecords > 0
                    ? round(($count / $this->totalRecords) * 100, 2)
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
        $this->info('Execution time: ' . round($executionTime, 2) . ' seconds');
    }
}
