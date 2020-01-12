<?php


namespace CIConfigGen\Console\Command;


use CIConfigGen\Detector\DetectCIFromGitConfig;
use CIConfigGen\Detector\DetectExistingCI;
use CIConfigGen\Json\JsonReader;
use CIConfigGen\ValueObject\Constants;
use CIConfigGen\Yaml\FilenameGenerator;
use CIConfigGen\Yaml\YamlPrinter;
use CIConfigGen\Yaml\YamlToJson;
use CIConfigGen\YamlGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MigrateCommand extends Command {

    /**
     * @var string
     */
    public const NAME = 'migrate';

    private $detectExistingCi;

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


    private $yamlToJson;

    public function __construct(
        SymfonyStyle $symfonyStyle,
        JsonReader $jsonReader,
        YamlGenerator $yamlGenerator,
        YamlPrinter $yamlPrinter,
        FilenameGenerator $filenameGenerator,
        DetectExistingCI $detectExistingCi,
        YamlToJson $yamlToJson
    )
    {
        $this->symfonyStyle = $symfonyStyle;
        $this->yamlGenerator = $yamlGenerator;
        $this->yamlPrinter = $yamlPrinter;
        $this->jsonReader = $jsonReader;
        $this->filenameGenerator = $filenameGenerator;
        $this->detectExistingCi = $detectExistingCi;
        $this->yamlToJson = $yamlToJson;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        // 1. Find CI Yaml file

        /** @var string $file [] */
        $ciService = $input->getArgument('file');
        $ciServiceName = key($ciService);
        $ciServiceFile = $ciService[$ciServiceName];
        $this->symfonyStyle->note('Detected: ' . $ciServiceFile);

        // 2. Choose alternative CI service
        $ciServices = Constants::CI_SERVICES;
        unset($ciServices[$ciServiceName]);
        $answer = $this->symfonyStyle->choice('Which CI service would you like to migrate to?', array_keys($ciServices));

        // 3. Migrate to selected service
        $json = $this->yamlToJson->convert($ciServiceFile);
        $ciYaml = $this->yamlGenerator->migrateFromJson($json, $answer);
        $outputFile = $this->filenameGenerator->generateFilename($answer);
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
        $this->setDescription('Migrate to alternative CI service');

        $ciService = $this->detectExistingCi->detect();
        $this->addArgument('file', InputArgument::OPTIONAL, 'CI yaml file', $ciService);
    }

}
