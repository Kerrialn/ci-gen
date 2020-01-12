<?php

declare(strict_types=1);

namespace CIConfigGen\Yaml;

use Nette\Utils\FileSystem;
use Symfony\Component\Yaml\Yaml;

class YamlToArray
{
    public function convert(string $filePath)
    {
        if (file_exists($filePath)) {
            $fileContent = FileSystem::read($filePath);
            return Yaml::parse($fileContent);
        }
    }
}
