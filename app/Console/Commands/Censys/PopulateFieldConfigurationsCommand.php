<?php

namespace App\Console\Commands\Censys;

use App\Models\CensysFieldConfiguration;
use Illuminate\Console\Command;

class PopulateFieldConfigurationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'censys:populate-field-configurations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate and populate the censys_field_configurations table from censys-query-params.csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = resource_path('csv/schema/censys-query-params.csv');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info('Reading censys-query-params.csv...');

        $file = fopen($filePath, 'r');
        fgetcsv($file, escape: '\\'); // Skip header row

        $configurations = [];
        $skipped = 0;
        $line = 1;

        while (($row = fgetcsv($file, escape: '\\')) !== false) {
            $line++;

            // Skip rows that don't have enough columns
            if (count($row) < 2) {
                $this->warn("Line $line: not enough columns (expected 2)");
                $skipped++;
                continue;
            }

            $protocol = trim($row[0] ?? '');
            $fields = trim($row[1] ?? '');

            // Skip if protocol is empty
            if (empty($protocol)) {
                $this->warn("Line $line: empty protocol");
                $skipped++;
                continue;
            }

            // Skip if fields is empty
            if (empty($fields)) {
                $this->warn("Line $line: empty fields");
                $skipped++;
                continue;
            }

            $configurations[] = [
                'protocol' => $protocol,
                'fields' => $fields,
            ];
        }

        fclose($file);

        if (empty($configurations)) {
            $this->warn('No valid configurations found to import.');

            return 1;
        }

        $this->info('Truncating and inserting ' . count($configurations) . ' configurations...');
        CensysFieldConfiguration::truncate();
        CensysFieldConfiguration::fillAndInsert($configurations);

        $count = CensysFieldConfiguration::count();
        $this->info("Successfully imported $count configurations.");

        if ($skipped > 0) {
            $this->warn("Skipped $skipped rows (missing data).");
        }

        return 0;
    }
}
