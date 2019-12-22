<?php

declare(strict_types=1);

namespace CIConfigGen\Contract;

interface WorkerInterface {

    public function isMatch(array $composerJsonData, string $level, string $expected): bool;

    public function decorate(array $composerJsonData, array $ciData): array;
}
