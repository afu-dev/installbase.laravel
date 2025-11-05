<?php

namespace App\Console\Commands\Country;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateCountriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate and populate the countries table from countries.csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = resource_path('csv/countries.csv');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info('Reading countries.csv...');

        $file = fopen($filePath, 'r', );
        fgetcsv($file, 0, ',', '"', ''); // Skip header row

        $countries = [];
        $skipped = 0;
        $rowNumber = 1; // Start at 1 (header is row 0)

        while (($row = fgetcsv($file, 0, ',', '"', '')) !== false) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Extract fields (assuming CSV columns: country_code2, country_code3, country, region, ciso_region, ciso_zone, operation_zone)
            $countryCode2 = trim($row[0] ?? '');
            $countryCode3 = trim($row[1] ?? '') ?: null;
            $country = trim($row[2] ?? '');
            $region = trim($row[3] ?? '') ?: null;
            $cisoRegion = trim($row[4] ?? '') ?: null;
            $cisoZone = trim($row[5] ?? '') ?: null;
            $operationZone = trim($row[6] ?? '') ?: null;

            // Skip if required fields are missing
            if (empty($countryCode2) || empty($country)) {
                $this->warn("Row #{$rowNumber}: missing required fields (country_code2 or country)");
                $skipped++;
                continue;
            }

            $countries[] = [
                'country_code2' => $countryCode2,
                'country_code3' => $countryCode3,
                'country' => $country,
                'region' => $region,
                'ciso_region' => $cisoRegion,
                'ciso_zone' => $cisoZone,
                'operation_zone' => $operationZone,
            ];
        }

        fclose($file);

        if (empty($countries)) {
            $this->warn('No valid countries found to import.');

            return 1;
        }

        $this->info('Truncating and inserting ' . count($countries) . ' countries...');
        DB::transaction(function () use ($countries) {
            Country::truncate();
            Country::fillAndInsert($countries);
        });

        $count = Country::count();
        $this->info("Successfully imported $count countries.");

        if ($skipped > 0) {
            $this->warn("Skipped $skipped rows (missing required fields).");
        }

        return 0;
    }
}
