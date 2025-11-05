<?php

namespace App\Console\Commands\Execution;

use App\Enums\Vendor;
use App\Models\BitsightExposedAsset;
use App\Models\CensysExposedAsset;
use App\Models\CensysFieldConfiguration;
use App\Models\Execution;
use App\Models\ImportError;
use App\Models\ShodanExposedAsset;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\String\Slugger\AsciiSlugger;

class WorkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execution:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the first available execution';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $execution = Execution::with('vendorQuery')
            ->whereNull('started_at')
            ->first();

        if (!$execution) {
            return self::FAILURE;
        }

        $execution->started_at = now();
        $execution->save();

        try {
            $count = match ($execution->vendorQuery->vendor) {
                Vendor::SHODAN => $this->scrapShodan($execution),
                Vendor::CENSYS => $this->scrapCensys($execution),
                Vendor::BITSIGHT => $this->processBitsight($execution),
            };

            $execution->count = $count;
        } catch (Exception $e) {
            $this->error("Caught the following exception:");
            $this->error($e->getMessage());
            $execution->errored = true;
        }

        $execution->finished_at = now();
        $execution->save();

        // Sleep 1 second between executions
        sleep(1);

        return self::SUCCESS;
    }

    private function scrapShodan(Execution $execution): int
    {
        $query = $execution->vendorQuery->query;

        $this->info("Processing Shodan query: $query");

        // Validate API key
        $apiKey = config('services.shodan.api_key');
        if (empty($apiKey)) {
            throw new Exception('SHODAN_API_KEY is not configured');
        }

        $client = Http::acceptJson()
            ->withQueryParameters(['key' => $apiKey]);

        $storage = Storage::disk('bronze');
        $slugger = new AsciiSlugger();
        $datePrefix = $execution->scan->created_at->format('Y/m/d');
        $destinationFolder = "shodan/$datePrefix/" . $slugger->slug($query);

        // First request
        $response = $client->get('https://api.shodan.io/shodan/host/search', ['query' => $query]);
        $data = $response->json();

        // Validate response structure
        if (!isset($data['total'])) {
            throw new Exception('Invalid Shodan API response: missing "total" key');
        }
        if (!isset($data['matches'])) {
            throw new Exception('Invalid Shodan API response: missing "matches" key');
        }

        $total = $data['total'];
        $totalPages = (int)ceil($total / 100);

        $this->info("Total results: $total | Total pages: $totalPages");

        // Save only the matches array from the first page
        $storage->put("$destinationFolder/page_001.json", json_encode($data['matches'], JSON_PRETTY_PRINT));

        $this->line("Stored page 1 - " . count($data['matches']) . ' matches');

        // Insert matches into database
        $this->insertShodanMatches($execution->id, $data['matches']);

        // Only fetch additional pages if there are more results
        if ($totalPages > 1) {
            foreach (range(2, $totalPages) as $page) {
                sleep(1); // Rate limiting: 1 second between requests

                $response = $client->get('https://api.shodan.io/shodan/host/search', ['query' => $query, 'page' => $page]);
                $data = $response->json();

                // Validate response structure
                if (!isset($data['matches'])) {
                    $this->warn("Invalid response on page $page - skipping");
                    continue;
                }

                $pageString = sprintf('%03d', $page);

                // Save only the matches array
                $storage->put("$destinationFolder/page_$pageString.json", json_encode($data['matches'], JSON_PRETTY_PRINT));

                $this->line("Stored page $page - " . count($data['matches']) . ' matches');

                // Insert matches into database
                $this->insertShodanMatches($execution->id, $data['matches']);
            }
        }

        $this->info("Completed Shodan query processing - Total hits: $total");

        return $total;
    }

    private function scrapCensys(Execution $execution): int
    {
        $query = $execution->vendorQuery->query;

        $this->info("Processing Censys query: $query");

        // Validate API credentials
        $apiId = config('services.censys.api_id');
        $apiSecret = config('services.censys.api_secret');
        $apiUrl = config('services.censys.api_url');

        if (empty($apiId)) {
            throw new Exception('CENSYS_API_ID is not configured');
        }
        if (empty($apiSecret)) {
            throw new Exception('CENSYS_API_SECRET is not configured');
        }
        if (empty($apiUrl)) {
            throw new Exception('CENSYS_API_URL is not configured');
        }

        // Get protocol and fields
        $protocol = $execution->vendorQuery->protocol ?: 'default';
        $fields = $this->getFieldsForProtocol($protocol);

        $this->line("Protocol: $protocol | Fields: $fields");

        $storage = Storage::disk('bronze');
        $slugger = new AsciiSlugger();
        $datePrefix = $execution->scan->created_at->format('Y/m/d');
        $destinationFolder = "censys/$datePrefix/" . $slugger->slug($query);

        // First request
        $result = $this->censysSearch($apiId, $apiSecret, $apiUrl, $query, $fields);

        $total = $result['total'];
        $totalPages = (int) ceil($total / 100);

        $this->info("Total results: $total | Total pages: $totalPages");

        // Store first page
        $storage->put("$destinationFolder/page_001.json", json_encode($result['hits'], JSON_PRETTY_PRINT));

        $this->line("Stored page 1 - " . count($result['hits']) . ' hits');

        // Insert hits into database
        $this->insertCensysMatches($execution->id, $result['hits']);

        // Paginate remaining pages if any
        $cursor = $result['next_cursor'] ?? null;

        for ($page = 2; $page <= $totalPages; $page++) {
            if ($cursor === null) {
                $this->warn("Cursor is null before reaching total pages (page $page/$totalPages)");
                break;
            }

            sleep(1); // Rate limiting: 1 second between requests

            $result = $this->censysSearch($apiId, $apiSecret, $apiUrl, $query, $fields, $cursor);

            $pageString = sprintf('%03d', $page);
            $storage->put("$destinationFolder/page_$pageString.json", json_encode($result['hits'], JSON_PRETTY_PRINT));

            $this->line("Stored page $page - " . count($result['hits']) . ' hits');

            // Insert hits into database
            $this->insertCensysMatches($execution->id, $result['hits']);

            $cursor = $result['next_cursor'] ?? null;
        }

        $this->info("Completed Censys query processing - Total hits: $total");

        return $total;
    }

    private function insertShodanMatches(int $executionId, array $matches): void
    {
        $records = array_map(function ($match) use ($executionId) {
            return [
                'execution_id' => $executionId,
                'ip' => data_get($match, 'ip_str'),
                'port' => data_get($match, 'port'),
                'module' => data_get($match, '_shodan.module'),
                'detected_at' => data_get($match, 'timestamp'),
                'raw_data' => data_get($match, 'data'),
                'hostnames' => implode(';', data_get($match, 'hostnames', [])),
                'entity' => data_get($match, 'org'),
                'isp' => data_get($match, 'isp'),
                'country_code' => data_get($match, 'location.country_code'),
                'city' => data_get($match, 'location.city'),
                'os' => data_get($match, 'os'),
                'asn' => data_get($match, 'asn'),
                'transport' => data_get($match, 'transport'),
                'product' => data_get($match, 'product'),
                'product_sn' => null, // @TODO: affect something f or product_sn?
                'version' => data_get($match, 'version'),
            ];
        }, $matches);

        ShodanExposedAsset::fillAndInsert($records);
    }

    private function getFieldsForProtocol(string $protocol): string
    {
        // Load all field configurations once (case-insensitive key)
        static $fieldConfigs = null;
        if ($fieldConfigs === null) {
            $fieldConfigs = CensysFieldConfiguration::all()->keyBy(function ($item) {
                return strtolower($item->protocol);
            });
        }

        // Normalize protocol to lowercase for lookup
        $protocolKey = strtolower($protocol);

        $protocolFields = $fieldConfigs->get($protocolKey)?->fields;
        $defaultFields = $fieldConfigs->get('default')?->fields;

        // Always include default fields
        if (empty($defaultFields)) {
            throw new Exception('No default Censys field configuration found in database');
        }

        // Combine protocol-specific fields with default fields
        $fields = array_filter([
            $protocolFields,
            $defaultFields,
        ]);

        return implode(',', $fields);
    }

    private function censysSearch(
        string $apiId,
        string $apiSecret,
        string $apiUrl,
        string $query,
        string $fields,
        ?string $cursor = null
    ): array {
        $payload = [
            'q' => $query,
            'per_page' => 100,
            'fields' => explode(',', $fields),
        ];

        if ($cursor !== null) {
            $payload['cursor'] = $cursor;
        }

        $response = Http::withBasicAuth($apiId, $apiSecret)
            ->acceptJson()
            ->post($apiUrl, $payload);

        if (!$response->successful()) {
            throw new Exception('Censys API request failed: ' . $response->body());
        }

        $data = $response->json();

        if (!isset($data['result']['hits'])) {
            throw new Exception('Invalid Censys API response: missing "result.hits" key');
        }

        return [
            'total' => $data['result']['total'] ?? 0,
            'hits' => $data['result']['hits'] ?? [],
            'next_cursor' => $data['result']['links']['next'] ?? null,
        ];
    }

    private function insertCensysMatches(int $executionId, array $hits): void
    {
        $records = array_map(function ($hit) use ($executionId) {
            // Extract first service for port, transport, and protocol info
            $firstService = data_get($hit, 'services.0', []);

            return [
                'execution_id' => $executionId,
                'ip' => data_get($hit, 'ip'),
                'port' => data_get($firstService, 'port'),
                'module' => data_get($firstService, 'service_name'),
                'detected_at' => data_get($hit, 'last_updated_at'),
                'raw_data' => json_encode($hit),
                'hostnames' => implode(';', array_merge(
                    data_get($hit, 'dns.names', []),
                    data_get($hit, 'dns.reverse_dns.names', [])
                )),
                'entity' => data_get($hit, 'whois.organization.name') ?: data_get($hit, 'whois.network.name'),
                'isp' => data_get($hit, 'autonomous_system.name'),
                'country_code' => data_get($hit, 'location.country_code'),
                'city' => data_get($hit, 'location.city'),
                'os' => null,
                'asn' => data_get($hit, 'autonomous_system.asn'),
                'transport' => data_get($firstService, 'transport_protocol'),
                'product' => data_get($firstService, 'software.product'),
                'product_sn' => null,
                'version' => data_get($firstService, 'software.version'),
            ];
        }, $hits);

        CensysExposedAsset::fillAndInsert($records);
    }

    private function processBitsight(Execution $execution): int
    {
        $this->info('Processing Bitsight CSV import');

        // Determine filename based on scan date and query type
        $scanDate = $execution->scan->created_at->format('Y_m_d');
        $period = $execution->vendorQuery->query; // e.g., 'weekly' or 'monthly'
        $filename = "bitsight_{$period}_{$scanDate}.csv";

        $this->line("Looking for file: {$filename}");

        // Check if file exists in bitsight-input disk
        $inputDisk = Storage::disk('bitsight-input');
        if (!$inputDisk->exists($filename)) {
            throw new Exception("Bitsight CSV file not found: {$filename}");
        }

        // Store in bronze layer
        $datePrefix = $execution->scan->created_at->format('Y/m/d');
        $bronzePath = "bitsight/{$datePrefix}/{$filename}";
        $bronzeDisk = Storage::disk('bronze');

        $this->line("Copying to bronze layer: {$bronzePath}");
        $bronzeDisk->put($bronzePath, $inputDisk->get($filename));

        // Read CSV from bronze layer
        $csvContent = $bronzeDisk->get($bronzePath);
        $lines = explode("\n", $csvContent);

        if (empty($lines)) {
            throw new Exception('CSV file is empty');
        }

        // Parse header
        $headerLine = array_shift($lines);
        $header = str_getcsv($headerLine, ',', '"', '');
        if (!$header) {
            throw new Exception('Failed to read CSV header');
        }

        $headerMap = array_flip($header);

        // Validate required columns exist
        $requiredColumns = ['Ip Str', 'Port', 'Module', 'Date', 'Transport'];
        foreach ($requiredColumns as $column) {
            if (!isset($headerMap[$column])) {
                throw new Exception("CSV missing required column: {$column}");
            }
        }

        $this->info('Processing CSV rows...');

        $assets = [];
        $skipped = 0;
        $duplicates = 0;
        $imported = 0;
        $rowNumber = 1; // Header is row 0

        foreach ($lines as $line) {
            $rowNumber++;

            // Skip empty lines
            if (trim($line) === '') {
                continue;
            }

            $row = str_getcsv($line, ',', '"', '');
            if ($row === false || count($row) < count($header)) {
                continue;
            }

            // Extract required fields
            $ip = trim($row[$headerMap['Ip Str']] ?? '');
            $port = trim($row[$headerMap['Port']] ?? '');
            $module = trim($row[$headerMap['Module']] ?? '');
            $date = trim($row[$headerMap['Date']] ?? '');
            $transport = trim($row[$headerMap['Transport']] ?? '');

            // Validate required fields
            if (empty($ip) || empty($port) || empty($module) || empty($date) || empty($transport)) {
                $missing = [];
                if (empty($ip)) {
                    $missing[] = 'Ip Str';
                }
                if (empty($port)) {
                    $missing[] = 'Port';
                }
                if (empty($module)) {
                    $missing[] = 'Module';
                }
                if (empty($date)) {
                    $missing[] = 'Date';
                }
                if (empty($transport)) {
                    $missing[] = 'Transport';
                }

                $errorMessage = 'Missing required field(s): ' . implode(', ', $missing);

                ImportError::create([
                    'vendor' => Vendor::BITSIGHT->value,
                    'source_file' => $filename,
                    'row_number' => $rowNumber,
                    'ip' => !empty($ip) ? $ip : null,
                    'port' => !empty($port) ? (int) floatval($port) : null,
                    'error_message' => $errorMessage,
                ]);

                $skipped++;
                continue;
            }

            // Parse date (format: "14/04/2024 00:00:00")
            $detectedAt = DateTime::createFromFormat('d/m/Y H:i:s', $date);
            if (!$detectedAt) {
                $errorMessage = "Invalid date format: '{$date}'";

                ImportError::create([
                    'vendor' => Vendor::BITSIGHT->value,
                    'source_file' => $filename,
                    'row_number' => $rowNumber,
                    'ip' => $ip,
                    'port' => (int) floatval($port),
                    'error_message' => $errorMessage,
                ]);

                $skipped++;
                continue;
            }

            // Convert port to integer
            $portInt = (int) floatval($port);

            // Build raw_data from entire row
            $rawData = [];
            foreach ($header as $index => $columnName) {
                $rawData[$columnName] = $row[$index] ?? null;
            }

            // Build asset record
            $asset = [
                'execution_id' => $execution->id,
                'ip' => $ip,
                'port' => $portInt,
                'module' => $module,
                'detected_at' => $detectedAt->format('Y-m-d H:i:s'),
                'transport' => $transport,
                'raw_data' => json_encode($rawData),
                'entity' => !empty($row[$headerMap['Entity Name']] ?? '') ? trim($row[$headerMap['Entity Name']]) : null,
                'country_code' => !empty($row[$headerMap['Country Code']] ?? '') ? trim($row[$headerMap['Country Code']]) : null,
                'city' => !empty($row[$headerMap['City']] ?? '') ? trim($row[$headerMap['City']]) : null,
                'hostnames' => null,
                'isp' => null,
                'os' => null,
                'asn' => null,
                'product' => null,
                'product_sn' => null,
                'version' => null,
            ];

            $assets[] = $asset;

            // Insert in batches of 1000
            if (count($assets) >= 1000) {
                $this->insertBitsightAssets($assets, $duplicates);
                $imported += count($assets) - $duplicates;
                $assets = [];
            }
        }

        // Insert any remaining assets
        if (!empty($assets)) {
            $beforeCount = $duplicates;
            $this->insertBitsightAssets($assets, $duplicates);
            $imported += count($assets) - ($duplicates - $beforeCount);
        }

        $this->info("Bitsight import completed!");
        $this->info("Successfully imported: {$imported}");

        if ($duplicates > 0) {
            $this->warn("Skipped duplicates: {$duplicates}");
        }

        if ($skipped > 0) {
            $this->warn("Skipped invalid rows: {$skipped}");
        }

        return $imported;
    }

    private function insertBitsightAssets(array &$assets, int &$duplicates): void
    {
        DB::transaction(function () use (&$assets, &$duplicates) {
            foreach ($assets as $asset) {
                try {
                    BitsightExposedAsset::create($asset);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Check if it's a duplicate key error (unique constraint violation)
                    if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                        $duplicates++;
                    } else {
                        // Re-throw if it's a different error
                        throw $e;
                    }
                }
            }
        });
    }
}
