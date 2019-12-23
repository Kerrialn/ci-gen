<?php

declare(strict_types=1);

namespace CIConfigGen\Console;

use CIConfigGen\Console\Command\GenerateConfigCommand;
use Symfony\Component\Console\Application;

final class CIConfigGenApplication extends Application
{
    public function __construct(GenerateConfigCommand $generateConfigCommand)
    {
        $this->add($generateConfigCommand);

        parent::__construct('CI Config Generator');
    }
}
