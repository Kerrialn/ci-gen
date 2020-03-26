<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Command\GenerateCommand;
use Symfony\Component\Console\Application;

final class ApplicationConsole extends Application
{
    public function __construct(GenerateCommand $generateCommand)
    {
        $this->add($generateCommand);
        parent::__construct(   '1.0' );
    }
}
