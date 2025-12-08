<?php

namespace App\Console\Commands\Account;

use App\Models\Account;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $filename = "account v2.xlsx";
        $filePath = resource_path("csv/schema/$filename");

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $this->info("Reading $filename...");

        $reader = IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);

        $accountsData = $spreadsheet->getActiveSheet()->toArray();

        if (empty($accountsData)) {
            $this->warn('No accounts found to import from the file.');
            return 1;
        }

        $headers = true;
        $accounts = [];

        foreach ($accountsData as $accountData) {
            if ($headers === true) {
                $headers = false;
                continue;
            }

            $accounts[] = [
                "entity" => $accountData[0] ? trim((string) $accountData[0]) : null,
                "sector" => $accountData[1] ? trim((string) $accountData[1]) : null,
                "entity_country" => $accountData[2] ? trim((string) $accountData[2]) : null,
                "url" => $accountData[3] ? trim((string) $accountData[3]) : null,
                "point_of_contact" => $accountData[4] ? trim((string) $accountData[4]) : null,
                "type_of_account" => $accountData[5] ? trim((string) $accountData[5]) : null,
                "account_manager" => $accountData[6] ? trim((string) $accountData[6]) : null,
            ];
        }

        if (empty($accounts)) {
            $this->warn('No valid accounts found to import.');
            return 1;
        }

        $this->info('Truncating and inserting ' . count($accounts) . ' accounts...');
        Account::truncate();
        Account::fillAndInsert($accounts);

        $count = Account::count();
        $this->info("Successfully imported $count accounts.");

        return 0;
    }
}
