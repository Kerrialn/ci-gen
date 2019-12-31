<?php


namespace CIConfigGen\Tests;

use CIConfigGen\Detector\DetectCIFromGitConfig;
use CIConfigGen\HttpKernel\CIConfigGenKernel;
use Iterator;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

class TestCIFromGitConfig extends AbstractKernelTestCase {

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
     * @param string $file
     */
    public function test(string $file): void
    {
        $gitConfig = parse_ini_file($file);
        $CIArray = $this->detectCIFromGitConfig->detect($gitConfig);

        $expectedArray = array(
            0 => 'Github Actions',
            1 => 'Travis CI',
            2 => 'Circle CI'
        );

        self::assertEquals($expectedArray, $CIArray);

    }

    public function provideData(): Iterator
    {

        yield [__DIR__ . '/Fixture/config'];
    }

}
