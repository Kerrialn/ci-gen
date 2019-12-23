<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use CIConfigGen\YamlGenerator;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;
use Symplify\PackageBuilder\Console\ShellCode;

final class GenerateConfigCommand extends Command
{
    /**
     * @var string
     */
    public const NAME = 'craft:generate';

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var YamlGenerator
     */
    private $yamlGenerator;


    public function __construct(SymfonyStyle $symfonyStyle, YamlGenerator $yamlGenerator)
    {
        parent::__construct();
        $this->symfonyStyle = $symfonyStyle;
        $this->yamlGenerator = $yamlGenerator;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $composerJsonFile = __DIR__ . '/../../../composer.json';

        $composerJsonContent = Json::decode(FileSystem::read($composerJsonFile), Json::FORCE_ARRAY);

        $ciYaml = $this->yamlGenerator->generateFromComposerJson($composerJsonContent);

        $yaml = Yaml::dump($ciYaml, 100, 4);
        FileSystem::write('example.yaml', $yaml);

        $this->symfonyStyle->success('Complete');

        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Generate a yml file for continuous delivery & integration platforms');
    }
}
