<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use CIConfigGen\CIDetector;
use CIConfigGen\ValueObject\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;

final class MigrateCommand extends Command
{
    /**
     * @var string
     */
    public const NAME = 'migrate';

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;
    /**
     * @var CIDetector
     */
    private $CIDetector;

    public function __construct(SymfonyStyle $symfonyStyle, CIDetector $CIDetector)
    {
        $this->symfonyStyle = $symfonyStyle;
        $this->CIDetector = $CIDetector;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $file */
        $file = $input->getArgument('file');

        $this->symfonyStyle->ask('da fuck is wrong with you?');

        return ShellCode::SUCCESS;
    }
}
