<?php declare(strict_types=1);

namespace CIConfigGen\Console\Command;

use CIConfigGen\Contract\WorkerInterface;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

final class GenerateConfigCommand extends Command
{
    /**
     * @var WorkerInterface[]
     */
    private $workers = [];

    /**
     * @param WorkerInterface[] $workers
     */
    public function __construct(array $workers)
    {
        parent::__construct();
        $this->workers = $workers;
    }

    protected function configure(): void
    {
        $this->setName('craft:generate');
        $this->setDescription('Generate a yml file for continuous delivery & integration platforms');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $composerJsonFile = __DIR__ . '/../../../composer.json';

        $object = Json::decode(FileSystem::read($composerJsonFile), Json::FORCE_ARRAY);

        // @todo modify


        $yaml = Yaml::dump($object, 2, 4, Yaml::DUMP_OBJECT_AS_MAP);
        FileSystem::write('example.yaml', $yaml);

        $command = $this->getApplication()->find('craft:refactor');
        $arguments = [
            'command' => 'craft:refactor'
        ];
        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        $output->writeln("Complete");

        return 1;
    }

}

