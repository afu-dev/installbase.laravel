<?php

namespace App\Console\Commands\Brand;

use App\Models\Brand;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PopulateBrandsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brands:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate and populate the brands table from brands.xlsx';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = "brands.xlsx";
        $filePath = resource_path("csv/schema/{$filename}");

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info("Reading {$filename}...");

        $reader = IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($filePath);

        $brandsData = $spreadsheet->getActiveSheet()->toArray();

        if (empty($brandsData)) {
            $this->warn('No brands found to import from the file.');
            return 1;
        }

        $headers = true;
        $brands = [];
        $skipped = 0;

        foreach ($brandsData as $brandDatum) {
            if ($headers === true) {
                $headers = false;
                continue;
            }

            $brandName = $brandDatum[0] ? trim((string) $brandDatum[0]) : null;

            if (empty($brandName)) {
                $skipped++;
                continue;
            }

            if (strlen($brandName) > 30) {
                $this->warn("Brand name too long (>30 chars), skipping: {$brandName}");
                $skipped++;
                continue;
            }

            $brands[] = [
                'brand' => $brandName,
            ];
        }

        if (empty($brands)) {
            $this->warn('No valid brands found to import.');
            return 1;
        }

        $this->info('Truncating and inserting ' . count($brands) . ' brands...');
        Brand::truncate();
        Brand::fillAndInsert($brands);

        $count = Brand::count();
        $this->info("Successfully imported {$count} brands.");

        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} invalid rows.");
        }

        return 0;
    }
}
