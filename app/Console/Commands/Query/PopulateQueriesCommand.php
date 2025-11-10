<?php

namespace App\Console\Commands\Query;

use App\Enums\Vendor;
use App\Models\Execution;
use App\Models\Query;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateQueriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate and populate the queries table from fingerprints.tsv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = resource_path('csv/schema/fingerprints.tsv');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info('Reading fingerprints.tsv...');

        $file = fopen($filePath, 'r');
        fgetcsv($file, 0, "\t"); // Skip header row

        $queries = [];
        $skipped = 0;

        while (($row = fgetcsv($file, 0, "\t")) !== false) {
            $id = trim($row[0] ?? '');
            if (empty($id) || !is_numeric($id)) {
                $this->error("Error: invalid ID supplied");
                $skipped++;
                continue;
            }

            // Skip rows that don't have enough columns
            if (count($row) < 7) {
                $this->warn("Row #$id: not enough column (min. 7)");
                $skipped++;
                continue;
            }

            // Skip if the required product field is missing
            $product = trim($row[1] ?? '');
            if (empty($product)) {
                $this->warn("Row #$id: empty product name");
                $skipped++;
                continue;
            }

            // Skip if the required query field is missing
            $query = trim($row[3] ?? '');
            if (empty($query)) {
                $this->warn("Row #$id: empty query field");
                $skipped++;
                continue;
            }

            // Determine vendor based on Shodan (column 5) or Censys (column 6)
            $vendor = null;
            if (($row[5] ?? '') === 'Y') {
                $vendor = Vendor::SHODAN->value;
            } elseif (($row[6] ?? '') === 'Y') {
                $vendor = Vendor::CENSYS->value;
            }

            // Skip if no vendor is specified
            if ($vendor === null) {
                $this->warn("Row #$id: invalid vendor!");
                $skipped++;
                continue;
            }

            $protocol = trim($row[2] ?? '');
            $queryType = trim($row[4] ?? '');

            $queries[] = [
                'id' => (int)$id,
                'product' => $product,
                'protocol' => $protocol ?: null,
                'query' => $query,
                'query_type' => $queryType ?: null,
                'vendor' => $vendor,
            ];
        }

        fclose($file);

        if (empty($queries)) {
            $this->warn('No valid queries found to import.');

            return 1;
        }

        // Add Bitsight CSV import query
        $queries[] = [
            'id' => 99999,
            'product' => 'Bitsight CSV Import',
            'protocol' => null,
            'query' => null,
            'query_type' => 'csv_import',
            'vendor' => Vendor::BITSIGHT->value,
        ];

        $this->info('Truncating and inserting ' . count($queries) . ' queries...');
        DB::transaction(function () use ($queries) {
            Execution::truncate();
            Query::truncate();
            Query::fillAndInsert($queries);
        });

        $count = Query::count();
        $this->info("Successfully imported $count queries.");

        if ($skipped > 0) {
            $this->warn("Skipped $skipped rows (missing data or no vendor specified).");
        }

        return 0;
    }
}
