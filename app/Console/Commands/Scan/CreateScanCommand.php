<?php

namespace App\Console\Commands\Scan;

use App\Enums\Vendor;
use App\Models\Execution;
use App\Models\Query;
use App\Models\Scan;
use Illuminate\Console\Command;

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
        // Ensure Bitsight Query exists for CSV imports
        Query::firstOrCreate(
            ['vendor' => Vendor::BITSIGHT, 'query' => 'weekly'],
            [
                'product' => 'Bitsight Weekly CSV',
                'protocol' => null,
                'query_type' => 'csv_import',
            ]
        );

        $queries = Query::all();

        if ($queries->isEmpty()) {
            $this->error('No queries found. Cannot create scan.');
            return self::FAILURE;
        }

        $scan = Scan::create();

        $executions = $queries->map(fn ($query) => [
            'scan_id' => $scan->id,
            'query_id' => $query->id,
        ])->toArray();

        Execution::fillAndInsert($executions);

        $this->info("Scan ID: {$scan->id}");
        $this->info("Executions created: {$queries->count()}");

        return self::SUCCESS;
    }
}
