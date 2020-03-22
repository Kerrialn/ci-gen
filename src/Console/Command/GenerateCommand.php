<?php

namespace App\Console\Command;

use App\Generators\YamlGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;

final class GenerateCommand extends Command {

    private const NAME = 'generate';
    private const CI_SERVICES = ['Github Actions'];
    private SymfonyStyle $symfonyStyle;
    private YamlGenerator $yamlGenerator;

    public function __construct(SymfonyStyle $style, YamlGenerator $yamlGenerator)
    {
        $this->symfonyStyle = $style;
        $this->yamlGenerator = $yamlGenerator;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $ciService = $this->symfonyStyle->choice('Please select a CI service:', self::CI_SERVICES);
        $output = $this->yamlGenerator->generateFromComposerJson($ciService);

        var_dump($output);

        $this->symfonyStyle->success('Generated');
        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('generate a CI Service yaml file');
    }
}
