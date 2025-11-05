<?php

namespace App\Console\Commands\Protocol;

use App\Models\Protocol;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateProtocolsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'protocols:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate and populate the protocols table from protocols.csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = resource_path('csv/protocols.csv');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info('Reading protocols.csv...');

        $file = fopen($filePath, 'r', );
        fgetcsv($file, 0, ',', '"', ''); // Skip header row

        $protocols = [];
        $skipped = 0;
        $rowNumber = 1; // Start at 1 (header is row 0)

        while (($row = fgetcsv($file, 0, ',', '"', '')) !== false) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Extract fields (assuming CSV columns: module, protocol, severity, description, modifier)
            $module = trim($row[0] ?? '');
            $protocol = trim($row[1] ?? '') ?: null;
            $severity = trim($row[2] ?? '') ?: null;
            $description = trim($row[3] ?? '') ?: null;
            $modifier = trim($row[4] ?? '') ?: null;

            // Skip if required field is missing
            if (empty($module)) {
                $this->warn("Row #{$rowNumber}: missing required field (module)");
                $skipped++;
                continue;
            }

            $protocols[] = [
                'module' => $module,
                'protocol' => $protocol,
                'severity' => $severity,
                'description' => $description,
                'modifier' => $modifier,
            ];
        }

        fclose($file);

        if (empty($protocols)) {
            $this->warn('No valid protocols found to import.');

            return 1;
        }

        $this->info('Truncating and inserting ' . count($protocols) . ' protocols...');
        DB::transaction(function () use ($protocols) {
            Protocol::truncate();
            Protocol::fillAndInsert($protocols);
        });

        $count = Protocol::count();
        $this->info("Successfully imported $count protocols.");

        if ($skipped > 0) {
            $this->warn("Skipped $skipped rows (missing required fields).");
        }

        return 0;
    }
}
