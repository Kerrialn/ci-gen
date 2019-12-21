#!/usr/bin/env php
<?php

require_once  __DIR__ . '/../vendor/autoload.php';

use CIConfigGen\WhatTimeCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new WhatTimeCommand());
$application->run();
