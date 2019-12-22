<?php

declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use CIConfigGen\Contract\WorkerInterface;
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
     * @var WorkerInterface[]
     */
    private $workers = [];

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @param WorkerInterface[] $workers
     */
    public function __construct(array $workers, SymfonyStyle $symfonyStyle)
    {
        parent::__construct();
        $this->workers = $workers;
        $this->symfonyStyle = $symfonyStyle;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $composerJsonFile = __DIR__ . '/../../../composer.json';

        $composerJsonContent = Json::decode(FileSystem::read($composerJsonFile), Json::FORCE_ARRAY);

        $ciYaml = [];
        foreach ($this->workers as $worker) {
            if ($worker->isMatch($composerJsonContent, 'require', 'php')) {
                $ciYaml['language'] = 'php';
                $ciYaml['install'] = '- composer install';
                $ciYaml['script'] = 'skip';

                $ciYaml['jobs']['include']['stage'] = 'preparing';
                $ciYaml['jobs']['include']['name:'] = 'prepare';
                $ciYaml['jobs']['include']['php'] = $composerJsonContent['require']['php'];
                $ciYaml['jobs']['include']['script'] = '- do something';

                if ($worker->isMatch($composerJsonContent, 'require-dev', 'phpstan/phpstan')) {
                    $ciYaml['jobs']['include']['stage'] = 'testing';
                    $ciYaml['jobs']['include']['name'] = 'phpstan/phpstan';
                    $ciYaml['jobs']['include']['php'] = $composerJsonContent['require']['php'];
                    $ciYaml['jobs']['include']['script'] = '- composer check-cs';
                }
            } else {
                $ciYaml['language'] = 'other';
            }
        }

        $yaml = Yaml::dump($ciYaml, 2, 4, Yaml::DUMP_OBJECT_AS_MAP);
        FileSystem::write('example.yaml', $yaml);

        $this->symfonyStyle->success('Complete');

        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('craft:generate');
        $this->setDescription('Generate a yml file for continuous delivery & integration platforms');
    }
}
