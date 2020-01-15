<?php

declare(strict_types=1);

namespace CIConfigGen\Yaml;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Symfony\Component\Yaml\Yaml;

final class YamlPrinter
{
    public function printYamlToFile(array $yaml, string $targetFile): void
    {
        $yamlContent = $this->printYamlToString($yaml);
        FileSystem::write($targetFile, $yamlContent);
    }

    public function printYamlToString(array $yaml): string
    {
        $yamlContent = Yaml::dump($yaml, 100, 4);

        $mainSectionSpacedYamlContent = Strings::replace($yamlContent, '#^(\w)#sm', PHP_EOL . '$1');

        return trim($mainSectionSpacedYamlContent) . PHP_EOL;
    }
}
