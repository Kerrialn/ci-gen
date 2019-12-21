<?php

namespace CIConfigGen;

use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WhatTimeCommand extends Command {

    protected function configure()
    {
        $this->setName('gc:WhatTime')->setDescription('Display current time')->setHelp('Print the current time to STDOUT');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new DateTime();
        $output->writeln('its now ' . $now->format('g:i a'));

        // success
        return 0;
    }

}

