<?php

declare(strict_types=1);

namespace CIConfigGen\Contract;

interface GeneratorsInterface
{
    public function isMatch(string $ciService): string;

    public function generate(array $composerJson): array;
}
