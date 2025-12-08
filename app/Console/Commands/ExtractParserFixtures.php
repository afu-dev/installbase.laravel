<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExtractParserFixtures extends Command
{
    protected $signature = 'fixtures:extract-parser-samples
                            {--limit=10 : Number of samples per module}
                            {--vendor= : Extract for specific vendor only (bitsight, shodan, censys)}
                            {--module= : Extract for specific module only}';

    protected $description = 'Extract diverse sample data from vendor tables to create test fixtures';

    // Modules that have data in Bitsight
    private array $bitsightModules = [
        'apcupsd', 'bacnet', 'codesys', 'dnp3', 'ethernetip',
        'ftp', 'iec-104', 'iec-61850', 'ion', 'knx',
        'modbus', 'opc-ua', 'other', 'snmp'
    ];

    // Modules that have data in Shodan
    private array $shodanModules = [
        'apcupsd', 'bacnet', 'ethernetip', 'ftp',
        'iec-61850', 'modbus', 'snmp'
    ];

    // All modules for Censys (future-proofing, no module data yet)
    private array $censysModules = [
        'apcupsd', 'bacnet', 'codesys', 'dnp3', 'ethernetip',
        'ftp', 'iec-104', 'iec-61850', 'ion', 'knx',
        'modbus', 'opc-ua', 'other', 'snmp'
    ];

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $vendorFilter = $this->option('vendor');
        $moduleFilter = $this->option('module');

        $this->info("Starting fixture extraction (limit: {$limit} samples per module)...\n");

        $totalCreated = 0;

        // Extract Bitsight fixtures
        if (!$vendorFilter || $vendorFilter === 'bitsight') {
            $this->info("📦 Extracting Bitsight fixtures...");
            $totalCreated += $this->extractVendorFixtures('bitsight', $this->bitsightModules, $limit, $moduleFilter);
        }

        // Extract Shodan fixtures
        if (!$vendorFilter || $vendorFilter === 'shodan') {
            $this->info("\n📦 Extracting Shodan fixtures...");
            $totalCreated += $this->extractVendorFixtures('shodan', $this->shodanModules, $limit, $moduleFilter);
        }

        // Extract Censys fixtures (random samples since no module data)
        if (!$vendorFilter || $vendorFilter === 'censys') {
            $this->info("\n📦 Extracting Censys fixtures (random samples)...");
            $totalCreated += $this->extractCensysFixtures($limit, $moduleFilter);
        }

        $this->newLine();
        $this->info("✅ Extraction complete! Created {$totalCreated} fixture files.");

        return self::SUCCESS;
    }

    private function extractVendorFixtures(string $vendor, array $modules, int $limit, ?string $moduleFilter): int
    {
        $table = "{$vendor}_exposed_assets";
        $created = 0;

        foreach ($modules as $module) {
            if ($moduleFilter && $moduleFilter !== $module) {
                continue;
            }

            // Handle SNMP variants
            $moduleVariants = $this->getModuleVariants($module, $vendor);

            foreach ($moduleVariants as $variant) {
                $count = DB::table($table)->where('module', $variant)->count();

                if ($count === 0) {
                    $this->warn("  ⚠️  {$module} ({$variant}): No data found, skipping");
                    continue;
                }

                $this->line("  → {$module} ({$variant}): Found {$count} records, extracting {$limit} samples...");

                try {
                    $samples = $this->extractDiverseSamples($table, $variant, $limit);

                    if ($samples->isEmpty()) {
                        $this->warn("    No diverse samples could be extracted");
                        continue;
                    }

                    $fixturesCreated = $this->writeSamplesToFixtures($vendor, $module, $variant, $samples);
                    $created += $fixturesCreated;

                    $this->info("    ✓ Created {$fixturesCreated} fixtures");
                } catch (\Exception $e) {
                    $this->error("    ✗ Error: {$e->getMessage()}");
                }
            }
        }

        return $created;
    }

    private function extractCensysFixtures(int $limit, ?string $moduleFilter): int
    {
        $table = 'censys_exposed_assets';
        $created = 0;

        $count = DB::table($table)->count();

        if ($count === 0) {
            $this->warn("  ⚠️  No Censys data found");
            return 0;
        }

        $this->line("  → Found {$count} records, extracting random samples...");

        // Since Censys has no module data, extract random samples and distribute across modules
        foreach ($this->censysModules as $module) {
            if ($moduleFilter && $moduleFilter !== $module) {
                continue;
            }

            try {
                $samples = DB::table($table)
                    ->select('raw_data', 'ip', 'country_code')
                    ->inRandomOrder()
                    ->limit($limit)
                    ->get();

                if ($samples->isEmpty()) {
                    continue;
                }

                $fixturesCreated = $this->writeSamplesToFixtures('censys', $module, $module, $samples);
                $created += $fixturesCreated;

                $this->info("    ✓ Created {$fixturesCreated} fixtures for {$module}");
            } catch (\Exception $e) {
                $this->error("    ✗ Error for {$module}: {$e->getMessage()}");
            }
        }

        return $created;
    }

    private function extractDiverseSamples(string $table, string $module, int $limit): \Illuminate\Support\Collection
    {
        // Strategy: Get more records than needed, then deduplicate for diversity
        $sampleSize = min($limit * 10, 1000);

        return DB::table($table)
            ->where('module', $module)
            ->select('raw_data', 'ip', 'country_code', 'detected_at')
            ->inRandomOrder()
            ->limit($sampleSize)
            ->get()
            ->unique(fn($row) =>
                // Deduplicate by IP prefix + country for diversity
                substr((string) $row->ip, 0, 7) . ($row->country_code ?? 'unknown'))
            ->take($limit)
            ->values();
    }

    private function writeSamplesToFixtures(string $vendor, string $module, string $variant, $samples): int
    {
        $created = 0;
        $fixtureDir = base_path("tests/fixtures/parsers/{$module}");

        // Ensure directory exists
        if (!is_dir($fixtureDir)) {
            mkdir($fixtureDir, 0755, true);
        }

        // For SNMP variants, use variant name in filename
        $modulePrefix = ($variant !== $module && str_starts_with($variant, 'snmp'))
            ? str_replace('_', '', $variant) // snmp_v2 -> snmpv2
            : $module;

        foreach ($samples as $index => $row) {
            // Shodan stores raw text, not JSON
            if ($vendor === 'shodan') {
                $filename = "{$vendor}_{$modulePrefix}_" . ($index + 1) . ".txt";
                $filepath = "{$fixtureDir}/{$filename}";

                file_put_contents($filepath, $row->raw_data);
                $created++;
                continue;
            }

            // Bitsight and Censys use JSON
            $data = json_decode((string) $row->raw_data, true);

            if (!$data) {
                $this->warn("      ⚠️  Sample {$index} has invalid JSON, skipping");
                continue;
            }

            $filename = "{$vendor}_{$modulePrefix}_" . ($index + 1) . ".json";
            $filepath = "{$fixtureDir}/{$filename}";

            file_put_contents(
                $filepath,
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );

            $created++;
        }

        return $created;
    }

    private function getModuleVariants(string $module, string $vendor): array
    {
        // Handle SNMP variants
        if ($module === 'snmp') {
            $variants = ['snmp'];

            // Check if snmp_v2 and snmp_v3 exist in the database
            $table = "{$vendor}_exposed_assets";

            if (DB::table($table)->where('module', 'snmp_v2')->exists()) {
                $variants[] = 'snmp_v2';
            }

            if (DB::table($table)->where('module', 'snmp_v3')->exists()) {
                $variants[] = 'snmp_v3';
            }

            return $variants;
        }

        return [$module];
    }
}
