<?php

namespace App\Console\Commands\Protocol;

use App\Models\Protocol;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
    protected $description = 'Truncate and populate the protocols table from protocol.xlsx';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = "protocol.xlsx";
        $filePath = resource_path("csv/schema/{$filename}");

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info("Reading {$filename}...");

        $reader = IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($filePath);

        $protocolsData = $spreadsheet->getActiveSheet()->toArray();

        if (empty($protocolsData)) {
            $this->warn('No protocols found to import from the file.');
            return 1;
        }

        $headers = true;
        $protocols = [];

        foreach ($protocolsData as $protocolData) {
            if ($headers === true) {
                $headers = false;
                continue;
            }

            $module = $protocolData[0] ? trim((string) $protocolData[0]) : null;
            if (is_null($module)) {
                $this->warn('Invalid module value (null), protocol is: ' . $protocolData[1]);
                continue;
            }

            if (isset($protocols[$module])) {
                $this->warn("Duplicate module value ({$module})");
                continue;
            }

            $protocols[$module] = [
                "module" => $module,
                "protocol" => $protocolData[1] ? trim((string) $protocolData[1]) : null,
                "modifier" => $protocolData[3] ? trim((string) $protocolData[3]) : null,
                "severity" => $protocolData[4] ? trim((string) $protocolData[4]) : null,
                "description" => $protocolData[5] ? trim((string) $protocolData[5]) : null,
            ];
        }

        if (empty($protocols)) {
            $this->warn('No valid protocols found to import.');
            return 1;
        }

        $this->info('Truncating and inserting ' . count($protocols) . ' protocols...');
        Protocol::truncate();
        Protocol::fillAndInsert($protocols);

        $count = Protocol::count();
        $this->info("Successfully imported {$count} protocols.");

        return 0;
    }
}
