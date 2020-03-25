<?php

declare(strict_types=1);

namespace App\Tests\Console\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Dotenv\Dotenv;

class GenerateCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/.env.test');

        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $executeSalesCommand = self::$kernel->getContainer()->get(
            'console.command.public_alias.App\Command\ExecuteSalesTaskCommand'
        );

        $application->add($executeSalesCommand);

        $command = $application->find('app:execute-sales-task');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command' => $command->getName(),
            ]
        );

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        // do your asserting stuff
    }
}
