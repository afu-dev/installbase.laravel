<?php

namespace App\Services\Parsers;

use App\Contracts\DataParserInterface;

class DataParserFactory
{
    protected array $parsers = [];

    public function __construct()
    {
        $this->registerParsers();
    }

    protected function registerParsers(): void
    {
        $parsers = config("parsers.map", []);

        foreach ($parsers as $key => $parserClass) {
            [$vendor, $module] = explode('.', $key);
            $this->register($vendor, $module, $parserClass);
        }
    }

    public function register(string $vendor, string $module, string $parserClass): void
    {
        $key = $this->getKey($vendor, $module);
        $this->parsers[$key] = $parserClass;
    }

    protected function getKey(string $vendor, string $module): string
    {
        return strtolower($vendor . "_" . $module);
    }

    public function make(string $vendor, string $module): DataParserInterface
    {
        $key = $this->getKey($vendor, $module);

        if (!isset($this->parsers[$key])) {
            throw new \InvalidArgumentException("No parser found for vendor $vendor and module $module");
        }

        return app($this->parsers[$key]);
    }
}
