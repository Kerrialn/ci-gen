<?php

namespace App\Console\Command;

use App\Contracts\GeneratorInterface;
use App\Generators\GeneratorSelector;
use App\Utils\Printers\PrinterSelector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;

final class GenerateCommand extends Command
{
    private const NAME = 'generate';
    private SymfonyStyle $symfonyStyle;
    private GeneratorSelector $generatorSelector;
    private PrinterSelector $printerSelector;

    /**
     * @var GeneratorInterface[]
     */
    private array $generators;

    /**
     * @param SymfonyStyle $style
     * @param GeneratorSelector $generatorSelector
     * @param GeneratorInterface[] $generators
     * @param PrinterSelector $printerSelector
     */
    public function __construct(
        SymfonyStyle $style,
        GeneratorSelector $generatorSelector,
        array $generators,
        PrinterSelector $printerSelector
    ) {
        $this->symfonyStyle = $style;
        $this->generatorSelector = $generatorSelector;
        $this->generators = $generators;
        $this->printerSelector = $printerSelector;
        parent::__construct();
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $generatorNames = [];
        foreach ($this->generators as $generator) {
            $generatorNames[] = $generator->getName();
        }

        $ciService = $this->symfonyStyle->choice('What do you want to generate?', $generatorNames);
        $this->symfonyStyle->progressStart(0);
        $intermediaryObject = $this->generatorSelector->generateFromComposerJson($ciService);
        $this->symfonyStyle->progressAdvance(50);
        $this->printerSelector->generateFileFromObject($intermediaryObject);
        $this->symfonyStyle->progressAdvance(100);
        $this->symfonyStyle->progressFinish();
        $this->symfonyStyle->success('Generated ' . $intermediaryObject->getFilename());

        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Generate continuous integration config yaml files or a dockerfile');
    }
}
