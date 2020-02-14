<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

use CIConfigGen\Contract\MigrateInterface;
use CIConfigGen\ValueObject\CiService;

final class GithubMigration implements MigrateInterface
{
    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::GITHUB_ACTIONS;
    }

    public function migrate(array $tempArray, string $destination): array
    {
        // 1. push to array with 'Github' patter
        $output = [];

        $output['name'] = $tempArray['name'] ?: 'Github Actions Yaml';
        $output['on'] = 'push';

        if ($tempArray['install']) {
            $output['jobs']['build']['runs-on'] = 'ubuntu-latest';
        }

        if ($tempArray['install']) {
            $output['jobs']['build']['steps'][] = [
                'name' => 'Checkout Repository',
                'uses' => 'actions/checkout@v1',
            ];
            $output['jobs']['build']['steps'][] = [
                'name' => 'Run composer install',
                'run' => 'composer install --prefer-dist',
            ];
        }

        if ($tempArray['jobs']) {
            $output = $this->migrateJob($tempArray, $output);
        }

        return $output;
    }

    public function migrateJob(array $tempArray, array $output): array
    {
        foreach ($tempArray['jobs'] as $key => $job) {
            if (! in_array($job['name'], $output['jobs'], true)) {
                $output['jobs']['build']['steps'][] = [
                    'name' => $job['name'] ?: 'test-' . $key,
                    'run' => '',
                ];
            }
        }
        return $output;
    }
}
