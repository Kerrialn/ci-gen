<?php

declare(strict_types=1);

namespace CIConfigGen\Contract;

interface MigrateInterface
{
    public function isMatch(string $ciService): bool;

    public function migrate(array $yamlConvertedToJson, string $destination): array;
}
