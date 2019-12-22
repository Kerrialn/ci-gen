<?php

declare(strict_types=1);

namespace CIConfigGen\Worker;

use CIConfigGen\Contract\WorkerInterface;

final class AddPhpVersionWorker implements WorkerInterface
{
    public function isMatch(array $composerJsonData, string $level,  string $expected): bool
    {
        return isset($composerJsonData[$level][$expected]);
    }

    public function decorate(array $composerJsonData, array $ciData): array
    {
        // @todo add php version to ci based on provided php version in composerJsonData

        return $ciData;
    }
}
