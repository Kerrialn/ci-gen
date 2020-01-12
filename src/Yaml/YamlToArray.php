<?php


namespace CIConfigGen\Yaml;


use Nette\Utils\FileSystem;
use Symfony\Component\Yaml\Yaml;

class YamlToJson {

    public function convert(string $filePath)
    {
        if (file_exists($filePath))
        {
            $fileContent = FileSystem::read($filePath);
            return Yaml::parse($fileContent);
        }
    }

}
