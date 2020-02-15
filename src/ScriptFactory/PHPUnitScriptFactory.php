<?php

declare(strict_types=1);

namespace CIConfigGen\ScriptFactory;

use CIConfigGen\Xml\XmlReader;

final class PHPUnitScriptFactory
{
    /**
     * @var XmlReader
     */
    private $xmlReader;

    public function __construct(XmlReader $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    public function create(): array
    {
        $phpunitXml = 'phpunit.xml';
        if (! file_exists($phpunitXml)) {
            return [];
        }

        $array = $this->xmlReader->readXmlToArray($phpunitXml);

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
