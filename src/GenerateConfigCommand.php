<?php

namespace CIConfigGen;

use http\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Serializer\Serializer;

class GenerateConfigCommand extends Command {

    protected function configure()
    {
        $this->setName('craft:generate')->setDescription('Will generate a yml file for continuous delivery platforms');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        try
        {
            $file = file('./composer.json');

            $object = json_decode(file_get_contents('./composer.json'));
            $yaml = Yaml::dump($object, 2, 4, Yaml::DUMP_OBJECT_AS_MAP);

            $fp = fopen('example.yaml', 'wb');
            fwrite($fp, $yaml);
            fclose($fp);

            $output->writeln("generating file");
            return 1;

        } catch (Exception $e)
        {
            echo 'Exception: ', $e->getMessage(), "\n";
        }

    }

}

