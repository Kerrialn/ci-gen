<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

use CIConfigGen\Contract\MigrateInterface;
use CIConfigGen\ValueObject\CiService;

final class GitlabMigration implements MigrateInterface
{
    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::GITLAB_CI;
    }

    public function migrate(MigrationIntermediaryObject $MigrationIntermediaryObject, string $destination): array
    {
        // 1. push to array with 'Gitlab' pattern
        $output = [];

        if ($MigrationIntermediaryObject['jobs']['include']) {
            $output['stages'] = [];

            foreach ($MigrationIntermediaryObject['jobs']['include'] as $include) {
                if (! in_array($include['stage'], $output['stages'], true)) {
                    $output['stages'][] = $include['stage'];
                }
            }
        }

        if ($MigrationIntermediaryObject['language']) {
            $output['language'] = $MigrationIntermediaryObject['language'];
        }

        if ($MigrationIntermediaryObject['install']) {
            $output['install'] = [
                'stage' => 'install',
                'script' => $MigrationIntermediaryObject['install'],
            ];
        }

        return $output;
    }
}
