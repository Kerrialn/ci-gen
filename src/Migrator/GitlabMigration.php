<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

use CIConfigGen\Contract\MigrateInterface;
use CIConfigGen\ValueObject\CiService;

final class GitlabMigration implements MigrateInterface {

    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::GITLAB_CI;
    }

    public function migrate(array $MigrationIntermediaryArray, string $destination): array
    {
        // 1. push to array with 'Gitlab' pattern
        $output = [];
        $output['stages'] = [];

        if ($MigrationIntermediaryArray['install'])
        {
            $output['stages'][] = 'build';

            $output['composer'] = [
                'stage' => 'build',
                'script' => $MigrationIntermediaryArray['install'],
            ];

        }

        if ($MigrationIntermediaryArray['jobs'])
        {
            foreach ($MigrationIntermediaryArray['jobs'] as $job)
            {

                if (!in_array($job['stage'], $output['stages'], true))
                {
                    $output['stages'][] = $job['stage'];
                }
            }

            foreach ($MigrationIntermediaryArray['jobs'] as $key => $job)
            {
                if (!in_array($job['name'], $output, true))
                {
                    $output[$job['name'] ? $job['name'] : ($job['stage'] . '_' . $key)] = [
                        'stage' => $job['stage'],
                        'image' => 'php:' . $job['php'] ?? null,
                        'script' => $job['script'],
                    ];
                }
            }
        }

        if ($MigrationIntermediaryArray['cache']['directories'])
        {
            $output['cache']['paths'] = $MigrationIntermediaryArray['cache']['directories'];
        }

        return $output;
    }
}
