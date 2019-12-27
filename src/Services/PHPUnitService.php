<?php


namespace CIConfigGen\Services;

use Symfony\Component\DomCrawler\Crawler;


class PHPUnitService {

    public function create(): array
    {

        $xml = simplexml_load_file('phpUnit.xml');
        $json = json_encode($xml);
        $array = json_decode($json, true);

        $tests = array();

        if ($array["testsuite"] && file_exists('phpUnit.xml'))
        {
            array_push($tests, 'vendor/bin/phpunit --testsuite main');
        } else
        {
            array_push($tests, 'skip');
        }

        return $tests;

    }
}
