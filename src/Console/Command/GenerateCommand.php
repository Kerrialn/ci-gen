<?php

namespace App\Console\Command;

use App\Contracts\GeneratorInterface;
use App\Generators\GithubActionsGenerator;
use App\Generators\YamlGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;

final class GenerateCommand extends Command {

    private const NAME = 'generate';

    private SymfonyStyle $symfonyStyle;

    private YamlGenerator $yamlGenerator;

    /**
     * @var GeneratorInterface[]
     */
    private array $generators;

    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(SymfonyStyle $style, YamlGenerator $yamlGenerator, array $generators)
    {
        $this->symfonyStyle = $style;
        $this->yamlGenerator = $yamlGenerator;
        parent::__construct();
        $this->generators = $generators;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $generatorNames = [];
        foreach ($this->generators as $generator) {
            $generatorNames[] = $generator->getName();
        }

        $ciService = $this->symfonyStyle->choice('Please select a CI service:', $generatorNames);

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
