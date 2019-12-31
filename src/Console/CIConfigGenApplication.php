<?php

declare(strict_types=1);

namespace CIConfigGen\Console;

use CIConfigGen\Console\Command\GenerateCommand;
use Symfony\Component\Console\Application;

final class CIConfigGenApplication extends Application {

    public function __construct(GenerateCommand $generateCommand)
    {
        $this->add($generateCommand);

        parent::__construct('CI Config Generator');
    }
}
