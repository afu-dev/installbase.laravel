<?php

namespace App\Console\Commands\Account;

use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate and populate the accounts table from accounts.csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = resource_path('csv/accounts.csv');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info('Reading accounts.csv...');

        $file = fopen($filePath, 'r', );
        fgetcsv($file, 0, ',', '"', ''); // Skip header row

        $accounts = [];
        $skipped = 0;
        $rowNumber = 1; // Start at 1 (header is row 0)

        while (($row = fgetcsv($file, 0, ',', '"', '')) !== false) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Extract fields (assuming CSV columns: entity, sector, entity_country, url, point_of_contact, type_of_account, account_manager)
            $entity = trim($row[0] ?? '');
            $sector = trim($row[1] ?? '') ?: null;
            $entityCountry = trim($row[2] ?? '') ?: null;
            $url = trim($row[3] ?? '') ?: null;
            $pointOfContact = trim($row[4] ?? '') ?: null;
            $typeOfAccount = trim($row[5] ?? '') ?: null;
            $accountManager = trim($row[6] ?? '') ?: null;

            // Skip if required field is missing
            if (empty($entity)) {
                $this->warn("Row #{$rowNumber}: missing required field (entity)");
                $skipped++;
                continue;
            }

            $accounts[] = [
                'entity' => $entity,
                'sector' => $sector,
                'entity_country' => $entityCountry,
                'url' => $url,
                'point_of_contact' => $pointOfContact,
                'type_of_account' => $typeOfAccount,
                'account_manager' => $accountManager,
            ];
        }

        fclose($file);

        if (empty($accounts)) {
            $this->warn('No valid accounts found to import.');

            return 1;
        }

        $this->info('Truncating and inserting ' . count($accounts) . ' accounts...');
        DB::transaction(function () use ($accounts) {
            Account::truncate();
            Account::fillAndInsert($accounts);
        });

        $count = Account::count();
        $this->info("Successfully imported $count accounts.");

        if ($skipped > 0) {
            $this->warn("Skipped $skipped rows (missing required fields).");
        }

        return 0;
    }
}
