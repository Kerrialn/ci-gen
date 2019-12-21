#!/usr/bin/env php
<?php

require_once  __DIR__ . '/../vendor/autoload.php';

use CIConfigGen\GenerateConfigCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new GenerateConfigCommand());
$application->run();
