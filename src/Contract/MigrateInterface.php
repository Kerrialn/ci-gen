<?php

declare(strict_types=1);

namespace CIConfigGen\Contract;

use CIConfigGen\Migrator\MigrationIntermediaryObject;

interface MigrateInterface
{
    public function isMatch(string $ciService): bool;

    public function migrate(MigrationIntermediaryObject $MigrationIntermediaryObject, string $destination): array;
}
