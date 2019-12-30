<?php

namespace CIConfigGen\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MigrateCommand extends Command
{
    /**
     * @var string
     */
    public const NAME = 'migrate';

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->ask('da fuck is wrong with you?');
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Migrates an existing CI service to a different service');

        $yamlFile = null;
        $this->addArgument('file', InputArgument::OPTIONAL, 'Path to existing CI yaml file', $yamlFile);
    }
}
