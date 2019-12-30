<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use CIConfigGen\Json\JsonReader;
use CIConfigGen\ValueObject\Constants;
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

    public function __construct(
        SymfonyStyle $symfonyStyle,
        JsonReader $jsonReader,
        YamlGenerator $yamlGenerator,
        YamlPrinter $yamlPrinter,
        FilenameGenerator $filenameGenerator
    ) {
        $this->symfonyStyle = $symfonyStyle;
        $this->yamlGenerator = $yamlGenerator;
        $this->yamlPrinter = $yamlPrinter;
        $this->jsonReader = $jsonReader;
        $this->filenameGenerator = $filenameGenerator;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $file */
        $file = $input->getArgument('file');

        // 1. read & get user input
        $ciServices = [];
        $gitConfig = parse_ini_file('.git/config');

        if (Strings::contains($gitConfig['url'], 'github')) {
            $this->symfonyStyle->note('Github detected');
            $ciServices[] = Constants::GITHUB_ACTIONS;
            $ciServices[] = Constants::TRAVIS_CI;
            $ciServices[] = Constants::CIRCLE_CI;
        } elseif (Strings::contains($gitConfig['url'], 'gitlab')) {
            $this->symfonyStyle->note('Gitlab detected');
            $ciServices[] = Constants::GITLAB_CI;
        } elseif (Strings::contains($gitConfig['url'], 'bitbucket')) {
            $this->symfonyStyle->note('Bitbucket detected');
            $ciServices[] = Constants::BITBUCKET_CI;
        } else {
            $ciServices[] = Constants::GITLAB_CI;
            $ciServices[] = Constants::TRAVIS_CI;
            $ciServices[] = Constants::CIRCLE_CI;
            $ciServices[] = Constants::GITHUB_ACTIONS;
            $ciServices[] = Constants::TRAVIS_CI;
        }

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
