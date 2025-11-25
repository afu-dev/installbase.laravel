<?php

namespace App\Providers;

use App\Services\Parsers\DataParserFactory;
use Illuminate\Support\ServiceProvider;

class DataParserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DataParserFactory::class, function ($app) {
            return new DataParserFactory();
        });
    }
}
