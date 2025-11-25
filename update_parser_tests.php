<?php

/**
 * Script to convert parser tests from dataProvider pattern to individual test methods
 * Converts tests to match ModbusParserTest pattern with individual methods for each fixture
 */

$testFiles = glob(__DIR__ . '/tests/Unit/Services/Parsers/**/*ParserTest.php');

$skipFiles = [
    'IonParserTest.php',
    'ModbusParserTest.php', // Bitsight version already correct
];

foreach ($testFiles as $file) {
    $basename = basename($file);

    // Skip files that are already in correct format
    if (in_array($basename, $skipFiles) && str_contains($file, 'Bitsight')) {
        echo "Skipping {$file} (already correct format)\n";
        continue;
    }

    $content = file_get_contents($file);

    // Check if it uses dataProvider
    if (!str_contains($content, '@dataProvider')) {
        echo "Skipping {$file} (no dataProvider found)\n";
        continue;
    }

    // Extract class name and parser name
    preg_match('/class (\w+ParserTest)/', $content, $classMatch);
    preg_match('/use App\\\\Services\\\\Parsers\\\\(\w+)\\\\(\w+Parser)/', $content, $parserMatch);

    if (!$classMatch || !$parserMatch) {
        echo "Warning: Could not extract class/parser name from {$file}\n";
        continue;
    }

    $vendor = strtolower($parserMatch[1]); // bitsight, censys, shodan
    $parserClass = $parserMatch[2];
    $module = strtolower(str_replace('Parser', '', $parserClass));

    // Determine file extension for fixtures
    $extension = ($vendor === 'shodan') ? 'txt' : 'json';

    // Generate individual test methods for fixtures 1-5
    $testMethods = '';
    for ($i = 1; $i <= 5; $i++) {
        $testMethods .= generateTestMethod($vendor, $module, $i, $parserClass, $extension);
    }

    // Build new test class
    $newContent = <<<PHP
<?php

namespace Tests\Unit\Services\Parsers\\{$parserMatch[1]};

use App\Services\Parsers\\{$parserMatch[1]}\\{$parserClass};
use PHPUnit\Framework\TestCase;

class {$classMatch[1]} extends TestCase
{
{$testMethods}}

PHP;

    // Write updated content
    file_put_contents($file, $newContent);
    echo "✓ Updated {$file}\n";
}

echo "\n✅ All parser tests updated!\n";

function generateTestMethod($vendor, $module, $number, $parserClass, $extension): string
{
    $methodName = "test_it_parses_{$vendor}_{$module}_data_{$number}";
    $fixturePath = "tests/fixtures/parsers/{$module}/{$vendor}_{$module}_{$number}.{$extension}";

    return <<<PHP
    public function {$methodName}(): void
    {
        \$parser = new {$parserClass}();

        \$data = file_get_contents("{$fixturePath}");

        \$result = \$parser->parse(\$data);

        \$this->assertEquals('not_parsed', \$result->vendor);
        \$this->assertNull(\$result->fingerprint);
        \$this->assertNull(\$result->version);
        \$this->assertNull(\$result->sn);
        \$this->assertNull(\$result->device_mac);
        \$this->assertNull(\$result->modbus_project_info);
        \$this->assertNull(\$result->opc_ua_security_policy);
        \$this->assertNull(\$result->is_guest_account_active);
        \$this->assertNull(\$result->registration_info);
        \$this->assertNull(\$result->secure_power_app);
        \$this->assertNull(\$result->nmc_card_num);
        \$this->assertNull(\$result->fingerprint_raw);
    }


PHP;
}
