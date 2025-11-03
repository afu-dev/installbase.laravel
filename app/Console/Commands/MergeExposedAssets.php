<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MergeExposedAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exposed-assets:merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge vendor exposed assets into unified exposed_assets table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // TODO: Implement merge logic
        return Command::SUCCESS;
    }
}
