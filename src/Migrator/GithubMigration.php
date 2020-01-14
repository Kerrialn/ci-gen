<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

use CIConfigGen\ValueObject\CiService;

class GithubMigration
{
    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::GITHUB_ACTIONS;
    }

    public function migrate(array $MigrationIntermediaryArray, string $destination): array
    {
        // 1. push to array with 'Github' pattern
        $output = [];

        $output['jobs'] = $MigrationIntermediaryArray['jobs'];

        return $output;
    }
}
