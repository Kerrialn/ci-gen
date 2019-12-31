<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use CIConfigGen\Detector\DetectCIFromGitConfig;
use CIConfigGen\Json\JsonReader;
use CIConfigGen\ValueObject\CiService;
use CIConfigGen\Yaml\FilenameGenerator;
use CIConfigGen\Yaml\YamlPrinter;
use CIConfigGen\YamlGenerator;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;

final class GenerateCommand extends Command
{
    /**
     * @var string
     */
    public const NAME = 'gen';

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
     * @var FilenameGenerator
     */
    private $filenameGenerator;

    /**
     * @var JsonReader
     */
    private $jsonReader;


    private $detectCIFromGitConfig;


    public function __construct(
        SymfonyStyle $symfonyStyle,
        JsonReader $jsonReader,
        YamlGenerator $yamlGenerator,
        YamlPrinter $yamlPrinter,
        FilenameGenerator $filenameGenerator,
        DetectCIFromGitConfig $detectCIFromGitConfig
    ) {
        $this->symfonyStyle = $symfonyStyle;
        $this->yamlGenerator = $yamlGenerator;
        $this->yamlPrinter = $yamlPrinter;
        $this->jsonReader = $jsonReader;
        $this->filenameGenerator = $filenameGenerator;
        $this->detectCIFromGitConfig = $detectCIFromGitConfig;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $file */
        $file = $input->getArgument('file');
        $gitConfig = parse_ini_file('.git/config');

        // 1. read & get user input
        $ciServices = $this->detectCIFromGitConfig->detect($gitConfig);
        $ciService = $this->symfonyStyle->choice('Please select a CI service:', $ciServices);
        $output->writeln('You selected: ' . $ciService);
        $composerJsonContent = $this->jsonReader->readFileToJson($file);

        // 2. process
        $ciYaml = $this->yamlGenerator->generateFromComposerJson($composerJsonContent, $ciService);

        // 3. print
        $outputFile = $this->filenameGenerator->generateFilename($ciService);
        $this->yamlPrinter->printYamlToFile($ciYaml, $outputFile);

        $outputSmartFile = new SmartFileInfo($outputFile);
        $this->symfonyStyle->success(
            sprintf('File "%s" was successfully created', $outputSmartFile->getRelativeFilePathFromCwd())
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
