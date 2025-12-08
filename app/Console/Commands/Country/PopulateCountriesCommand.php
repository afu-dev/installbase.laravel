<?php

namespace App\Console\Commands\Country;

use App\Models\Country;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $filename = "countries.xlsx";
        $filePath = resource_path("csv/schema/$filename");

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info("Reading $filename...");

        $reader = IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);

        $countriesData = $spreadsheet->getActiveSheet()->toArray();

        if (empty($countriesData)) {
            $this->warn('No countries found to import from the file.');
            return 1;
        }

        $headers = true;
        $countries = [];

        foreach ($countriesData as $countryDatum) {
            if ($headers === true) {
                $headers = false;
                continue;
            }

            $countries[] = [
                'country_code2' => $countryDatum[1] ? trim((string) $countryDatum[1]) : null,
                'country_code3' => $countryDatum[2] ? trim((string) $countryDatum[2]) : null,
                'country' => $countryDatum[0] ? trim((string) $countryDatum[0]) : null,
                'region' => $countryDatum[3] ? trim((string) $countryDatum[3]) : null,
                'ciso_region' => $countryDatum[5] ? trim((string) $countryDatum[5]) : null,
                'ciso_zone' => $countryDatum[4] ? trim((string) $countryDatum[4]) : null,
                'operation_zone' => $countryDatum[6] ? trim((string) $countryDatum[6]) : null,
            ];
        }

        if (empty($countries)) {
            $this->warn('No valid countries found to import.');
            return 1;
        }

        $this->info('Truncating and inserting ' . count($countries) . ' countries...');
        Country::truncate();
        Country::fillAndInsert($countries);

        $count = Country::count();
        $this->info("Successfully imported $count countries.");

        return 0;
    }
}
