<?php

declare(strict_types=1);

namespace Drupal8\Rector\Deprecation\DrupalSetMessageRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

class DrupalSetMessageRectorTest extends AbstractRectorTestCase
{
    /**
     * @covers ::refactor
     *
     * @dataProvider provideData
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<<string>>
     */
    public static function provideData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__.'/fixture');
    }

    public function provideConfigFilePath(): string
    {
        // must be implemented
        return __DIR__.'/config/configured_rule.php';
    }
}
