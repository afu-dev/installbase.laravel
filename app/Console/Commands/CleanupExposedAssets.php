<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupExposedAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exposed-assets:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete exposed assets not detected for 90 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // TODO: Implement cleanup logic
        return Command::SUCCESS;
    }
}
