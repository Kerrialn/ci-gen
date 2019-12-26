<?php

declare(strict_types=1);

namespace CIConfigGen\Json;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;

final class JsonReader
{
    public function readFileToJson(string $filePath): array
    {
        $fileContent = FileSystem::read($filePath);
        return Json::decode($fileContent, Json::FORCE_ARRAY);
    }
}
