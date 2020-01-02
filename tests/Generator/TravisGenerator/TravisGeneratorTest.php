<?php

declare(strict_types=1);

namespace CIConfigGen\Tests\Generator\TravisGenerator;

use CIConfigGen\HttpKernel\CIConfigGenKernel;
use CIConfigGen\Json\JsonReader;
use CIConfigGen\ValueObject\CiService;
use CIConfigGen\Yaml\YamlPrinter;
use CIConfigGen\YamlGenerator;
use Iterator;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

final class TravisGeneratorTest extends AbstractKernelTestCase
{
    /**
     * @var YamlGenerator
     */
    private $yamlGenerator;

    /**
     * @var JsonReader
     */
    private $jsonReader;

    /**
     * @var YamlPrinter
     */
    private $yamlPrinter;

    protected function setUp(): void
    {
        $this->bootKernel(CIConfigGenKernel::class);
        $this->jsonReader = self::$container->get(JsonReader::class);
        $this->yamlPrinter = self::$container->get(YamlPrinter::class);
        $this->yamlGenerator = self::$container->get(YamlGenerator::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $jsonFile, string $expectedYamlFile): void
    {
        $json = $this->jsonReader->readFileToJson($jsonFile);

        $yaml = $this->yamlGenerator->generateFromComposerJson($json, CiService::TRAVIS_CI);
        $yamlContent = $this->yamlPrinter->printYamlToString($yaml);

        $this->assertStringEqualsFile($expectedYamlFile, $yamlContent);
    }

    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/first.json', __DIR__ . '/Fixture/expected_first.yaml'];
    }
}
