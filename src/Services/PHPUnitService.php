<?php

declare(strict_types=1);

namespace CIConfigGen\Services;

use Nette\Utils\Json;

final class PHPUnitService
{
    public function create(): array
    {
        $phpunitXml = 'phpunit.xml';
        if (! file_exists($phpunitXml)) {
            return [];
        }

        $xml = simplexml_load_file($phpunitXml);
        $json = Json::encode($xml, Json::PRETTY);
        $array = Json::decode($json, Json::FORCE_ARRAY);

        $tests = [];

        if ($array['testsuite']) {
            $testsuiteName = $array['testsuite']['@attributes']['name'];
            $tests[] = sprintf('vendor/bin/phpunit --testsuite %s', $testsuiteName);
        } else {
            $tests[] = 'skip';
        }

        return $tests;
    }
}
