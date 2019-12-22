<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TimeCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new DateTime();
        $output->writeln('its now ' . $now->format('g:i a'));

        return 0;
    }

    protected function configure(): void
    {
        $this->setName('craft:time')->setDescription('Display current time')->setHelp(
            'Print the current time to STDOUT'
        );
    }
}
