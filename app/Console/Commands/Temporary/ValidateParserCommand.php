<?php

namespace App\Console\Commands\Temporary;

use App\Models\BitsightExposedAsset;
use App\Models\CensysExposedAsset;
use App\Models\ShodanExposedAsset;
use App\Services\Parsers\DataParserFactory;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class ValidateParserCommand extends Command
{
    protected $signature = 'temporary:validate-parser {vendor} {module}';

    protected $description = 'Validate parser against all database records (fail-fast mode)';

    public function handle(): int
    {
        $vendor = $this->argument('vendor');
        $module = $this->argument('module');

        $this->info("Validating {$vendor}.{$module} parser...");
        $this->newLine();

        // Get model class based on vendor
        $modelClass = match ($vendor) {
            'bitsight' => BitsightExposedAsset::class,
            'shodan' => ShodanExposedAsset::class,
            'censys' => CensysExposedAsset::class,
            default => null,
        };

        if (!$modelClass) {
            $this->error("Invalid vendor: {$vendor}");
            $this->info("Valid vendors: bitsight, shodan, censys");
            return Command::FAILURE;
        }

        // Get total count
        $total = $modelClass::where('module', $module)->count();

        if ($total === 0) {
            $this->warn("No records found for {$vendor}.{$module}");
            return Command::SUCCESS;
        }

        // Initialize parser
        try {
            $factory = new DataParserFactory();
            $parser = $factory->make($vendor, $module);
        } catch (\InvalidArgumentException $e) {
            $this->error("Failed to create parser: {$e->getMessage()}");
            return Command::FAILURE;
        }

        // Create progress bar
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->setFormat(ProgressBar::FORMAT_VERBOSE);

        $successCount = 0;
        $failed = false;

        // Process records in chunks
        try {
            $modelClass::where('module', $module)
                ->chunkById(100, function ($records) use ($parser, $progressBar, &$successCount, &$failed, $vendor, $module) {
                    foreach ($records as $record) {
                        try {
                            $parser->parse($record->raw_data);
                            $successCount++;
                            $progressBar->advance();
                        } catch (\Throwable $e) {
                            // Stop on first failure
                            $progressBar->finish();
                            $this->newLine(2);

                            $this->error("✗ Parser failed on record ID: {$record->id}");
                            $this->newLine();
                            $this->line("Error: {$e->getMessage()}");
                            $this->newLine();

                            // Create test fixture
                            $fixturePath = $this->createTestFixture($vendor, $module, $record);
                            $this->info("Test fixture created: {$fixturePath}");
                            $this->newLine();

                            $this->line("Next steps:");
                            $this->line("1. Review the fixture file");
                            $this->line("2. Add a test case in tests/Unit/Services/Parsers/" . ucfirst($vendor) . "/" . ucfirst($module) . "ParserTest.php");
                            $this->line("3. Fix the parser to handle this case");
                            $this->line("4. Run validator again");

                            $failed = true;

                            // Stop processing by returning false
                            return false;
                        }
                    }
                });
        } catch (\Exception $e) {
            // This shouldn't happen, but just in case
            $this->error("Unexpected error: {$e->getMessage()}");
            return Command::FAILURE;
        }

        if ($failed) {
            return Command::FAILURE;
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✓ All {$successCount} records parsed successfully!");

        return Command::SUCCESS;
    }

    private function createTestFixture(string $vendor, string $module, $record): string
    {
        // Get fixture directory
        $fixtureDir = base_path("tests/fixtures/parsers/{$module}");

        // Create directory if it doesn't exist
        if (!is_dir($fixtureDir)) {
            mkdir($fixtureDir, 0755, true);
        }

        // Determine file extension based on vendor
        // Shodan uses plain text, Bitsight and Censys use JSON
        $extension = $vendor === 'shodan' ? 'txt' : 'json';

        // Find next available number
        $existingFixtures = glob("{$fixtureDir}/{$vendor}_{$module}_*.{$extension}");
        $maxNumber = 0;

        foreach ($existingFixtures as $fixture) {
            if (preg_match("/{$vendor}_{$module}_(\d+)\.{$extension}$/", $fixture, $matches)) {
                $maxNumber = max($maxNumber, (int)$matches[1]);
            }
        }

        $nextNumber = $maxNumber + 1;
        $fixturePath = "{$fixtureDir}/{$vendor}_{$module}_{$nextNumber}.{$extension}";

        // Save raw_data based on vendor format
        if ($vendor === 'shodan') {
            // Shodan: Save as plain text
            file_put_contents($fixturePath, $record->raw_data);
        } else {
            // Bitsight/Censys: Save as pretty-printed JSON
            $json = json_encode(json_decode($record->raw_data), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            file_put_contents($fixturePath, $json);
        }

        return $fixturePath;
    }
}
