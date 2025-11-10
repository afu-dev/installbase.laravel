<?php

namespace App\Console\Commands\Scan;

use App\Enums\Vendor;
use App\Models\Execution;
use App\Models\Query;
use App\Models\Scan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateScanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new scan with executions for all queries';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $queries = Query::all();

        if ($queries->isEmpty()) {
            $this->error('No queries found. Cannot create scan.');
            return self::FAILURE;
        }

        $scan = Scan::create();

        $executions = [];

        foreach ($queries as $query) {
            // For Bitsight, create one execution per CSV file in the input directory
            if ($query->vendor === Vendor::BITSIGHT) {
                $csvFiles = Storage::disk('bitsight-input')->files();
                $csvFiles = array_filter($csvFiles, fn ($file) => str_ends_with(strtolower($file), '.csv'));

                if (empty($csvFiles)) {
                    $this->warn('No CSV files found in bitsight-input directory. Skipping Bitsight executions.');
                    continue;
                }

                foreach ($csvFiles as $filename) {
                    $executions[] = [
                        'scan_id' => $scan->id,
                        'query_id' => $query->id,
                        'source_file' => basename($filename),
                    ];
                }

                $this->info("Found " . count($csvFiles) . " Bitsight CSV file(s) to process.");
            } else {
                // For other vendors, create a single execution
                $executions[] = [
                    'scan_id' => $scan->id,
                    'query_id' => $query->id,
                    'source_file' => null,
                ];
            }
        }

        if (empty($executions)) {
            $this->error('No executions to create. Cannot create scan.');
            return self::FAILURE;
        }

        Execution::fillAndInsert($executions);

        $this->info("Scan ID: {$scan->id}");
        $this->info("Executions created: " . count($executions));

        return self::SUCCESS;
    }
}
