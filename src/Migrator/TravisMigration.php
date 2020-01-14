<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

use CIConfigGen\Contract\MigrateInterface;
use CIConfigGen\ValueObject\CiService;

final class TravisMigration implements MigrateInterface
{
    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::TRAVIS_CI;
    }

    public function migrate(array $MigrationIntermediaryArray, string $destination): array
    {

        // 1. push to array with 'Travis' pattern
        return [];
    }
}
