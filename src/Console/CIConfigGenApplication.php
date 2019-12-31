<?php

declare(strict_types=1);

namespace CIConfigGen\Console;

use CIConfigGen\Console\Command\GenerateCommand;
use CIConfigGen\Console\Command\MigrateCommand;
use Symfony\Component\Console\Application;

final class CIConfigGenApplication extends Application {

    public function __construct(GenerateCommand $generateCommand, MigrateCommand $migrateCommand)
    {
        $this->add($generateCommand);
        $this->add($migrateCommand);

        parent::__construct('CI Config Generator');
    }
}
