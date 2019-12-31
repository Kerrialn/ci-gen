<?php

declare(strict_types=1);

namespace CIConfigGen\Tests;

use CIConfigGen\Detector\DetectCIFromGitConfig;
use CIConfigGen\HttpKernel\CIConfigGenKernel;
use Iterator;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

class TestCIFromGitConfig extends AbstractKernelTestCase
{
    /**
     * @var DetectCIFromGitConfig
     */
    private $detectCIFromGitConfig;

    protected function setUp(): void
    {
        $this->bootKernel(CIConfigGenKernel::class);
        $this->detectCIFromGitConfig = self::$container->get(DetectCIFromGitConfig::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $file): void
    {
        $gitConfig = parse_ini_file($file);
        $CIArray = $this->detectCIFromGitConfig->detect($gitConfig);

        $expectedArray = [
            0 => 'Github Actions',
            1 => 'Travis CI',
            2 => 'Circle CI',
        ];

        self::assertSame($expectedArray, $CIArray);
    }

    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/config'];
    }
}
