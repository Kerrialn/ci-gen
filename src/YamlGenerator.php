<?php

declare(strict_types=1);

namespace CIConfigGen;

use CIConfigGen\Contract\WorkerInterface;
use Symfony\Component\Console\Input\InputArgument;

final class YamlGenerator {

    /**
     * @var WorkerInterface[]
     */
    private $workers = [];
    /**
     * @param WorkerInterface[] $workers
     * @param string $userManager
     */
    public function __construct(array $workers)
    {
        $this->workers = $workers;
    }

    public function generateFromComposerJson(array $composerJson, string $ciService): array
    {

        $ciYaml = [];
        foreach ($this->workers as $worker)
        {

            if ($worker->isMatch($composerJson, 'require', 'php'))
            {
                $ciYaml['language'] = 'php';
                $ciYaml['install'] = '- composer install';
                $ciYaml['script'] = 'skip';

                $ciYaml['jobs']['include']['stage'] = 'preparing';
                $ciYaml['jobs']['include']['name:'] = 'prepare';
                $ciYaml['jobs']['include']['php'] = $composerJson['require']['php'];
                $ciYaml['jobs']['include']['script'] = '- do something';

                if ($worker->isMatch($composerJson, 'require-dev', 'phpstan/phpstan'))
                {
                    $ciYaml['jobs']['include']['stage'] = 'testing';
                    $ciYaml['jobs']['include']['name'] = 'phpstan/phpstan';
                    $ciYaml['jobs']['include']['php'] = $composerJson['require']['php'];
                    $ciYaml['jobs']['include']['script'] = '- composer check-cs';
                }

                $ciYaml['jobs']['include']['stage'] = 'preparing';
                $ciYaml['jobs']['include']['name:'] = 'prepare';
                $ciYaml['jobs']['include']['php'] = $composerJson['require']['php'];
                $ciYaml['jobs']['include']['script'] = '- do something';

            } else
            {
                $ciYaml['language'] = 'other';
            }
        }

        return $ciYaml;
    }
}
