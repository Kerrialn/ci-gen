<?php

namespace CIConfigGen;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class GenerateConfigCommand extends Command {

    protected function configure()
    {
        $this->setName('craft:generate')->setDescription('Generate a yml file for continuous delivery & integration platforms');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        try
        {
            $file = file('./composer.json');

            $object = json_decode(file_get_contents('./composer.json'));
            $yaml = Yaml::dump($object, 2, 4, Yaml::DUMP_OBJECT_AS_MAP);

            $fp = fopen('example.yaml', 'wb');
            $output->writeln("Generating yaml file");
            fwrite($fp, $yaml);
            fclose($fp);

            $command = $this->getApplication()->find('craft:refactor');
            $arguments = [
                'command' => 'craft:refactor'
            ];
            $input = new ArrayInput($arguments);
            $command->run($input, $output);

            $output->writeln("Complete");
            return 1;

        } catch (Exception $e)
        {
            echo 'Exception: ', $e->getMessage(), "\n";
        }

    }

}

