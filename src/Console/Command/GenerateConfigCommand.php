<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use CIConfigGen\Json\JsonReader;
use CIConfigGen\Yaml\YamlPrinter;
use CIConfigGen\YamlGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;

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

    /**
     * @var YamlPrinter
     */
    private $yamlPrinter;

    /**
     * @var JsonReader
     */
    private $jsonReader;

    public function __construct(
        SymfonyStyle $symfonyStyle,
        JsonReader $jsonReader,
        YamlGenerator $yamlGenerator,
        YamlPrinter $yamlPrinter
    ) {
        $this->symfonyStyle = $symfonyStyle;
        $this->yamlGenerator = $yamlGenerator;
        $this->yamlPrinter = $yamlPrinter;
        $this->jsonReader = $jsonReader;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $file */
        $file = $input->getArgument('file');

        // the main application :)
        // 1. read
        $composerJsonContent = $this->jsonReader->readFileToJson($file);

        // 2. process
        $ciYaml = $this->yamlGenerator->generateFromComposerJson($composerJsonContent);

        // 3. print
        $outputFile = getcwd() . '/example.yaml';
        $this->yamlPrinter->printYamlToFile($ciYaml, $outputFile);

        $outputSmartFile = new SmartFileInfo($outputFile);
        $this->symfonyStyle->success(
            sprintf('File "%s" was successfuly created', $outputSmartFile->getRelativeFilePathFromCwd())
        );

        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Generate a yml file for continuous delivery & integration platforms');

        $composerJsonFile = __DIR__ . '/../../../composer.json';
        $this->addArgument('file', InputArgument::OPTIONAL, 'Path to composer.json file', $composerJsonFile);
    }
}
