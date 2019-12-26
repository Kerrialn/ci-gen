<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;


use CIConfigGen\Json\JsonReader;
use CIConfigGen\Yaml\FilenameGenerator;
use CIConfigGen\Yaml\userInput;
use CIConfigGen\Yaml\YamlPrinter;
use CIConfigGen\YamlGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;

final class GenerateConfigCommand extends Command {

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
     * @var FilenameGenerator
     */
    private $fileNameGenerator;

    /**
     * @var JsonReader
     */
    private $jsonReader;

    public function __construct(
        SymfonyStyle $symfonyStyle,
        JsonReader $jsonReader,
        YamlGenerator $yamlGenerator,
        YamlPrinter $yamlPrinter,
        FilenameGenerator $fileNameGenerator
    )
    {
        $this->symfonyStyle = $symfonyStyle;
        $this->yamlGenerator = $yamlGenerator;
        $this->yamlPrinter = $yamlPrinter;
        $this->jsonReader = $jsonReader;
        $this->fileNameGenerator = $fileNameGenerator;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $file */
        $file = $input->getArgument('file');

        // 1. read & get user input
        $ciServices = [];
        $gitConfig = parse_ini_file('.git/config');

        if (strpos($gitConfig['url'], 'github') !== false)
        {
            array_push($ciServices, 'GithubActions', 'TravisCI', 'CircleCI');

        } elseif (strpos($gitConfig['url'], 'gitlab') !== false)
        {
            array_push($ciServices, 'GitlabCI');

        } elseif (strpos($gitConfig['url'], 'bitbucket') !== false)
        {
            array_push($ciServices, 'BitbucketCI');
        } else
        {
            array_push($ciServices, 'GitlabCI', 'TravisCI', 'CircleCI', 'GithubActions', 'TravisCI');
        }

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select a CI service (default: GitlabCI)',
            $ciServices,
            'GitlabCI'
        );

        $question->setErrorMessage(' CI service %s is invalid.');
        $ciService = $helper->ask($input, $output, $question);
        $output->writeln('You selected: ' . $ciService);
        $composerJsonContent = $this->jsonReader->readFileToJson($file);

        // 2. process
        $ciYaml = $this->yamlGenerator->generateFromComposerJson($composerJsonContent, $ciService);

        // 3. print
        $outputFile = $this->fileNameGenerator->generateFilename($ciService);
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
