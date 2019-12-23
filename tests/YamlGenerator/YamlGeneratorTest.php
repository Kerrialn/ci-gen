<?php declare(strict_types=1);

namespace CIConfigGen\Tests\YamlGenerator;

use _HumbugBoxb49a3c9618cf\Nette\Utils\Json;
use CIConfigGen\HttpKernel\CIConfigGenKernel;
use CIConfigGen\Json\JsonReader;
use CIConfigGen\Yaml\YamlPrinter;
use CIConfigGen\YamlGenerator;
use Iterator;
use Nette\Utils\FileSystem;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

final class YamlGeneratorTest extends AbstractKernelTestCase
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

        $yaml = $this->yamlGenerator->generateFromComposerJson($json);
        $yamlContent = $this->yamlPrinter->printYamlToString($yaml);

        $this->assertStringEqualsFile($expectedYamlFile, $yamlContent);
    }

    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/first.json', __DIR__ . '/Fixture/expected_first.yaml'];
        yield [__DIR__ . '/Fixture/empty.json', __DIR__ . '/Fixture/expected_empty.yaml'];
    }
}
