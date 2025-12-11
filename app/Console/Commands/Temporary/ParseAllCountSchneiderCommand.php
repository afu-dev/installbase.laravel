<?php

namespace App\Console\Commands\Temporary;

use App\Enums\Vendor;
use App\Models\BitsightExposedAsset;
use App\Models\ShodanExposedAsset;
use App\Services\Parsers\DataParserFactory;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class ParseAllCountSchneiderCommand extends Command
{
    protected $signature = 'temporary:parse-all';

    protected $description = 'Parse all data from Shodan and Bitsight and count how many vendor = schneider exists';

    /** @throws Exception */
    public function handle(DataParserFactory $parserFactory): void
    {
        $this->handleVendor(Vendor::SHODAN, $parserFactory);
        $this->handleVendor(Vendor::BITSIGHT, $parserFactory);
    }

    /**
     * @param Vendor $vendor
     * @param DataParserFactory $parserFactory
     * @return void
     * @throws Exception
     */
    private function handleVendor(Vendor $vendor, DataParserFactory $parserFactory): void
    {
        $vendorModel = match ($vendor) {
            Vendor::BITSIGHT => BitsightExposedAsset::class,
            Vendor::SHODAN => ShodanExposedAsset::class,
            Vendor::CENSYS => throw new Exception("Censys usage is deprecated"),
        };

        $count = $vendorModel::count();

        $this->output->info("Running the parser on {$vendor->name} ({$count} records to go)...");

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->setFormat(ProgressBar::FORMAT_DEBUG);
        $progressBar->start();
        $path = Storage::path("{$vendor->value}.csv");
        $csvResource = fopen($path, "w");

        $vendorModel::chunkById(1000, function (Collection $records) use ($parserFactory, $vendor, $progressBar, $csvResource) {
            $results = [];
            /** @var BitsightExposedAsset|ShodanExposedAsset $record */
            foreach ($records as $record) {
                try {
                    $parser = $parserFactory->make($vendor->value, $record->module ?? 'other');
                    $devices = $parser->parse($record->raw_data);
                } catch (\TypeError $e) {
                    dd($e, $record);
                }
                foreach ($devices as $device) {
                    $results[] = [$record->ip, $device->vendor];
                }
                $progressBar->advance();
            }
            foreach ($results as $result) {
                fputcsv($csvResource, $result);
            }
        });
        $progressBar->finish();
    }
}
