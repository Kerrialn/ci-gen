<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

use CIConfigGen\Contract\MigrateInterface;
use CIConfigGen\ValueObject\CiService;

class GithubMigration implements MigrateInterface{

    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::GITHUB_ACTIONS;
    }

    public function migrate(array $tempArray, string $destination): array
    {
        // 1. push to array with 'Github' patter
        $output = [];

        $output['name'] = $tempArray['name'] ? $tempArray['name'] : 'Github Actions Yaml';


        if($tempArray['on']){
            $output['on']['pull_request'] = null;
            $output['on']['push']['branches'][] = 'master';
        }

        if ($tempArray['install'])
        {
            $output['jobs']['build']['runs-on'] = 'ubuntu-latest';
            $output['jobs']['build']['steps'][] = ['uses' => 'actions/checkout@v2'];
            $output['jobs']['build']['steps'][] = ['uses' => 'shivammathur/setup-php@v1'];
            $output['jobs']['build']['steps'][] = ['with' => ['php-version'=> 7.2, 'coverage'=>'none']];
            $output['jobs']['build']['steps'][] = ['run' => 'composer install --no-progress'];
            $output['jobs']['build']['steps'][] = ['run' => 'composer check-docs'];
        }

        return $output;
    }
}
