<?php

declare(strict_types=1);

namespace CIConfigGen\Contract;

interface GeneratorsInterface
{
    public function isMatch(string $ciService): bool;

    public function generate(array $composerJson): array;
}
