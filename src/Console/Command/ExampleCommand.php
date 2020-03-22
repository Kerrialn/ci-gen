<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;

final class ExampleCommand extends Command
{
    private const NAME = 'example';
    private SymfonyStyle $symfonyStyle;

    public function __construct(SymfonyStyle $style)
    {
        $this->symfonyStyle = $style;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->success('Hello World!');

        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Say hello to the world');
    }
}
