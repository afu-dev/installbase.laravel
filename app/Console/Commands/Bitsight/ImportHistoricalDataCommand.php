<?php

namespace App\Console\Commands\Bitsight;

use App\Enums\Vendor;
use App\Models\BitsightExposedAsset;
use App\Models\ImportError;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportHistoricalDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitsight:import-historical {file_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import historical Bitsight CSV file into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);

        $filePath = $this->argument('file_path');
        $sourceFile = basename($filePath);

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        // Count total lines using wc -l for efficiency
        $totalLines = (int) trim(shell_exec("wc -l < " . escapeshellarg($filePath)));
        $totalRows = $totalLines - 1; // Subtract header row

        $this->info("Reading CSV file: {$filePath}");
        $this->info("Total rows to process: {$totalRows}");

        $file = fopen($filePath, 'r');
        if (!$file) {
            $this->error("Failed to open file: {$filePath}");

            return 1;
        }

        // Read and store header for mapping
        $header = fgetcsv($file, 0, ',', '"', '');
        if (!$header) {
            $this->error("Failed to read CSV header");
            fclose($file);

            return 1;
        }

        // Create header index map for easy column access
        $headerMap = array_flip($header);

        // Create and start progress bar
        $progressBar = $this->output->createProgressBar($totalRows);
        $progressBar->start();

        $assets = [];
        $skipped = 0;
        $duplicates = 0;
        $rowNumber = 1; // Start at 1 (header is row 0)

        while (($row = fgetcsv($file, 0, ',', '"', '')) !== false) {
            $progressBar->advance();
            $rowNumber++;

            // Validate required fields exist in CSV
            if (!isset($headerMap['Ip Str'], $headerMap['Port'], $headerMap['Module'], $headerMap['Date'], $headerMap['Transport'])) {
                $this->error("CSV missing required columns");
                fclose($file);

                return 1;
            }

            $ip = trim($row[$headerMap['Ip Str']] ?? '');
            $port = trim($row[$headerMap['Port']] ?? '');
            $module = trim($row[$headerMap['Module']] ?? '');
            $date = trim($row[$headerMap['Date']] ?? '');
            $transport = trim($row[$headerMap['Transport']] ?? '');

            // Skip rows with missing required fields
            if (empty($ip) || empty($port) || empty($module) || empty($date) || empty($transport)) {
                $missing = [];
                if (empty($ip)) {
                    $missing[] = 'Ip Str';
                }
                if (empty($port)) {
                    $missing[] = 'Port';
                }
                if (empty($module)) {
                    $missing[] = 'Module';
                }
                if (empty($date)) {
                    $missing[] = 'Date';
                }
                if (empty($transport)) {
                    $missing[] = 'Transport';
                }

                $errorMessage = 'Missing required field(s): ' . implode(', ', $missing);

                // Log to database
                ImportError::create([
                    'vendor' => Vendor::BITSIGHT->value,
                    'source_file' => $sourceFile,
                    'row_number' => $rowNumber,
                    'ip' => !empty($ip) ? $ip : null,
                    'port' => !empty($port) ? (int) floatval($port) : null,
                    'error_message' => $errorMessage,
                ]);

                $ipInfo = !empty($ip) ? " (IP: {$ip})" : '';
                $this->warn("Row #{$rowNumber}{$ipInfo}: {$errorMessage}");
                $skipped++;
                continue;
            }

            // Parse date (format: "14/04/2024 00:00:00")
            $detectedAt = DateTime::createFromFormat('d/m/Y H:i:s', $date);
            if (!$detectedAt) {
                $errorMessage = "Invalid date format: '{$date}'";

                // Log to database
                ImportError::create([
                    'vendor' => Vendor::BITSIGHT->value,
                    'source_file' => $sourceFile,
                    'row_number' => $rowNumber,
                    'ip' => $ip,
                    'port' => !empty($port) ? (int) floatval($port) : null,
                    'error_message' => $errorMessage,
                ]);

                $this->warn("Row #{$rowNumber}: {$errorMessage} - skipping");
                $skipped++;
                continue;
            }

            // Convert port to integer (CSV has float like 443.0)
            $portInt = (int) floatval($port);

            // Build raw_data from entire row
            $rawData = [];
            foreach ($header as $index => $columnName) {
                $rawData[$columnName] = $row[$index] ?? null;
            }

            // Build asset record
            $asset = [
                'execution_id' => null, // Historical imports have no execution
                'ip' => $ip,
                'port' => $portInt,
                'module' => $module,
                'detected_at' => $detectedAt->format('Y-m-d H:i:s'),
                'transport' => $transport,
                'raw_data' => json_encode($rawData),
                'entity' => !empty($row[$headerMap['Entity Name']] ?? '') ? trim($row[$headerMap['Entity Name']]) : null,
                'country_code' => !empty($row[$headerMap['Country Code']] ?? '') ? trim($row[$headerMap['Country Code']]) : null,
                'city' => !empty($row[$headerMap['City']] ?? '') ? trim($row[$headerMap['City']]) : null,
                // Fields not in Bitsight CSV, set to null
                'hostnames' => null,
                'isp' => null,
                'os' => null,
                'asn' => null,
                'product' => null,
                'product_sn' => null,
                'version' => null,
            ];

            $assets[] = $asset;

            // Insert in batches of 1000 to avoid memory issues
            if (count($assets) >= 1000) {
                $this->insertAssets($assets, $duplicates);
                $assets = [];
            }
        }

        fclose($file);

        // Insert any remaining assets
        if (!empty($assets)) {
            $this->insertAssets($assets, $duplicates);
        }

        // Finish progress bar
        $progressBar->finish();
        $this->newLine(2);

        $total = $rowNumber - 1; // Subtract header row
        $imported = $total - $skipped - $duplicates;

        $this->info("Import completed!");
        $this->info("Total rows: {$total}");
        $this->info("Successfully imported: {$imported}");

        if ($duplicates > 0) {
            $this->warn("Skipped duplicates: {$duplicates}");
        }

        if ($skipped > 0) {
            $this->warn("Skipped invalid rows: {$skipped}");
        }

        $elapsedTime = microtime(true) - $startTime;
        $this->info(sprintf("Execution time: %.2f seconds", $elapsedTime));

        return 0;
    }

    /**
     * Insert assets into database, handling duplicates
     */
    private function insertAssets(array &$assets, int &$duplicates): void
    {
        DB::transaction(function () use (&$assets, &$duplicates) {
            foreach ($assets as $asset) {
                try {
                    BitsightExposedAsset::create($asset);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Check if it's a duplicate key error (unique constraint violation)
                    if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                        $duplicates++;
                    } else {
                        // Re-throw if it's a different error
                        throw $e;
                    }
                }
            }
        });
    }
}
