<?php


namespace App\Utils\Printers;

use App\Contracts\PrinterInterface;
use App\Intermediary\IntermediaryGenerateObject;
use App\Utils\FilenameGenerator;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Symfony\Component\Yaml\Yaml;

final class YamlPrinter implements PrinterInterface
{
    private const PRINTER_OUTPUT = 'yaml';
    private FilenameGenerator $filenameGenerator;

    /**
     *
     * @param $filenameGenerator
     */
    public function __construct(FilenameGenerator $filenameGenerator)
    {
        $this->filenameGenerator = $filenameGenerator;
    }


    public function isMatch(string $output): bool
    {
        return $output === self::PRINTER_OUTPUT;
    }

    public function generate(IntermediaryGenerateObject $intermediaryGenerateObject): void
    {
        $fileContent = $this->convertFileContent($intermediaryGenerateObject->getFileContent());
        FileSystem::write($intermediaryGenerateObject->getFilename(), $fileContent);
    }

    public function convertFileContent(array $fileContent) : string
    {
        $yamlContent = Yaml::dump($fileContent, 100, 4);

        $mainSectionSpacedYamlContent = Strings::replace($yamlContent, '#^(\w)#sm', PHP_EOL . '$1');

        return trim($mainSectionSpacedYamlContent) . PHP_EOL;
    }

}
