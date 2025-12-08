<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withTypeCoverageLevel(5)
    ->withDeadCodeLevel(20)
    ->withCodeQualityLevel(24)
    ->withCodingStyleLevel(20)
    ->withSkip([
        __DIR__ . '/bootstrap/cache/*',
        \Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector::class
    ]);
