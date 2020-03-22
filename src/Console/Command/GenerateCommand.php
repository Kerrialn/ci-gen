<?php

namespace App\Console\Command;

use App\Contracts\GeneratorInterface;
use App\Generators\GeneratorSelector;
use App\Utils\FilenameGenerator;
use App\Utils\GenerateYamlFile;
use App\Utils\YamlPrinter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\Exception\FileNotFoundException;
use Symplify\SmartFileSystem\SmartFileInfo;

final class GenerateCommand extends Command
{
    private const NAME = 'generate';

    private SymfonyStyle $symfonyStyle;

    private GeneratorSelector $generatorSelector;

    private GenerateYamlFile $generateYamlFile;

    private YamlPrinter $yamlPrinter;

    private FilenameGenerator $filenameGenerator;

    /**
     * @var GeneratorInterface[]
     */
    private array $generators;

    /**
     * @param SymfonyStyle $style
     * @param GeneratorSelector $generatorSelector
     * @param GeneratorInterface[] $generators
     * @param GenerateYamlFile $generateYamlFile
     * @param YamlPrinter $yamlPrinter
     * @param FilenameGenerator $filenameGenerator
     */
    public function __construct(
        SymfonyStyle $style,
        GeneratorSelector $generatorSelector,
        array $generators,
        GenerateYamlFile $generateYamlFile,
        YamlPrinter $yamlPrinter,
        FilenameGenerator $filenameGenerator
    )
    {
        $this->symfonyStyle = $style;
        $this->generatorSelector = $generatorSelector;
        $this->generators = $generators;
        $this->generateYamlFile = $generateYamlFile;
        $this->yamlPrinter = $yamlPrinter;
        $this->filenameGenerator = $filenameGenerator;
        parent::__construct();
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $generatorNames = [];
        foreach ($this->generators as $generator) {
            $generatorNames[] = $generator->getName();
        }

        $ciService = $this->symfonyStyle->choice('Please select a CI service:', $generatorNames);
        $fileName = $this->filenameGenerator->generateFilename($ciService);
        $fileContent = $this->generatorSelector->generateFromComposerJson($ciService);
        $this->yamlPrinter->printYamlToFile($fileContent, $fileName);
        $outputSmartFile = new SmartFileInfo($fileName);

        $this->symfonyStyle->success(
            sprintf('File "%s" was successfully created', $outputSmartFile->getRelativeFilePathFromCwd())
        );
        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('generate a CI Service yaml file');
    }
}
