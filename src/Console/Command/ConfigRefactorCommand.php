<?php declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class ConfigRefactorCommand extends Command
{
    protected function configure()
    {
        $this->setName('craft:refactor')->setDescription('Refactors & formats the generated yaml file');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new DateTime();
        $output->writeln('refactoring & formatting yaml');

        return 0;
    }

}

