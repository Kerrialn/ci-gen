<?php

declare(strict_types=1);

namespace CIConfigGen\Contract;

interface GeneratorInterface
{
    public function isMatch(string $ciService): bool;

    public function generate(array $composerJson): array;

    public function getFilename(): string;
}
