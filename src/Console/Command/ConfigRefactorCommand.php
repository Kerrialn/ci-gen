<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\ShellCode;

final class ConfigRefactorCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('refactoring & formatting yaml');
        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('craft:refactor');
        $this->setDescription('Refactors & formats the generated yaml file');
    }
}
