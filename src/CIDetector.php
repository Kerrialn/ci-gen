<?php


namespace CIConfigGen;


use CIConfigGen\ValueObject\Constants;

class CIDetector {

    public function run(): string
    {
        foreach (Constants::CI_SERVICES as $service)
        {
            if (file_exists($service))
            {
                return $service;
            }
        }

    }

}
