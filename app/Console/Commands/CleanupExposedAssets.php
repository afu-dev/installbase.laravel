<?php

namespace App\Console\Commands;

use App\Models\DetectedExposure;
use Illuminate\Console\Command;

class CleanupExposedAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exposed-assets:cleanup {--dry-run : Preview deletions without executing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete detected exposures not seen for 90 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $cutoffDate = now()->subDays(90);

        $this->info("Finding detected exposures with last_detected_at before {$cutoffDate->toDateTimeString()}...");

        // Find exposures that haven't been detected for 90+ days
        $query = DetectedExposure::whereNotNull('last_detected_at')
            ->where('last_detected_at', '<', $cutoffDate);

        $count = $query->count();

        if ($count === 0) {
            $this->info('No detected exposures found to clean up.');

            return Command::SUCCESS;
        }

        if ($dryRun) {
            $this->warn("[DRY RUN] Would soft delete {$count} detected exposure(s)");
            $this->table(
                ['IP', 'Port', 'Module', 'Last Detected'],
                $query->limit(10)->get()->map(fn($exposure) => [
                    $exposure->ip,
                    $exposure->port,
                    $exposure->module ?? 'N/A',
                    $exposure->last_detected_at->toDateTimeString(),
                ])->toArray()
            );

            if ($count > 10) {
                $this->info("... and " . ($count - 10) . " more");
            }

            return Command::SUCCESS;
        }

        // Confirm before deletion
        if (!$this->confirm("Soft delete {$count} detected exposure(s)?", false)) {
            $this->info('Cleanup cancelled.');

            return Command::SUCCESS;
        }

        // Perform soft delete
        $deleted = $query->delete();

        $this->info("Successfully soft deleted {$deleted} detected exposure(s).");

        return Command::SUCCESS;
    }
}
