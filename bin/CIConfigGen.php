#!/usr/bin/env php
<?php

require_once  __DIR__ . '/../vendor/autoload.php';

use CIConfigGen\Console\CIConfigGenApplication;
use CIConfigGen\HttpKernel\CIConfigGenKernel;
use Symfony\Component\Console\Application;

$ciConfigGenKernel = new CIConfigGenKernel(mt_rand(1, 10000), 'dev');
$ciConfigGenKernel->boot();

$container = $ciConfigGenKernel->getContainer();

/** @var CIConfigGenApplication $application */
$application = $container->get(CIConfigGenApplication::class);
exit($application->run());
