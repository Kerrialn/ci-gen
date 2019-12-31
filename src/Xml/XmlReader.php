<?php

declare(strict_types=1);

namespace CIConfigGen\Xml;

use Nette\Utils\Json;

final class XmlReader
{
    public function readXmlToArray(string $xmlFile): array
    {
        $xml = simplexml_load_file($xmlFile);
        $json = Json::encode($xml, Json::PRETTY);

        return Json::decode($json, Json::FORCE_ARRAY);
    }
}
