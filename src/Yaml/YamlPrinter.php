<?php

declare(strict_types=1);

namespace CIConfigGen\Yaml;

use Nette\Utils\FileSystem;
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
        return Yaml::dump($yaml, 100, 4);
    }
}
