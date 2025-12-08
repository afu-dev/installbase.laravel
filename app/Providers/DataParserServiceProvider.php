<?php

namespace App\Providers;

use App\Services\Parsers\DataParserFactory;
use Illuminate\Support\ServiceProvider;

class DataParserServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(DataParserFactory::class, fn ($app) => new DataParserFactory());
    }
}
