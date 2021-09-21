<?php

declare(strict_types=1);

namespace PHPSu\Tests\Config;

use PHPSu\Config\TempSshConfigFile;
use PHPUnit\Framework\TestCase;

final class TempSshConfigFileTest extends TestCase
{
    private string $oldCwd = '';

    public function setUp(): void
    {
        $cwd = getcwd();
        assert(is_string($cwd));
        $this->oldCwd = $cwd;
        chdir(__DIR__ . '/../fixtures');
        exec(sprintf('rm -rf %s', escapeshellarg(__DIR__ . '/../fixtures/.phpsu/')));
    }

    public function testConstruct(): void
    {
        $file = new TempSshConfigFile();
        $this->assertSame('', implode('', iterator_to_array($file)));
        $this->assertFileExists(__DIR__ . '/../fixtures/.phpsu/config/ssh_config');
    }

    public function testConstructDifferentFolder(): void
    {
        $this->markTestSkipped('Skipped because it does only work if user is not root');
//        $reflection = new \ReflectionClass(TempSshConfigFile::class);
//        $property = $reflection->getProperty('fileName');
//        $property->setAccessible(true);
//        $oldValue = $property->getValue();
//        $property->setValue('/root/.phpsu/ssh_config');
//        static::expectException(\Exception::class);
//        static::expectExceptionMessage(sprintf('Directory "%s" was not created', '/root/.phpsu'));
//        try {
//            new TempSshConfigFile();
//        } finally {
//            $property->setValue($oldValue);
//        }
    }

    public function tearDown(): void
    {
        exec(sprintf('rm -rf %s', escapeshellarg(__DIR__ . '/../fixtures/.phpsu/')));
        chdir($this->oldCwd);
    }
}
