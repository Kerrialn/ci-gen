<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\ShellCode;

final class TimeCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new DateTime();
        $output->writeln('its now ' . $now->format('g:i a'));

        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('craft:time');
        $this->setDescription('Display current time');
        $this->setHelp('Print the current time to STDOUT');
    }
}
