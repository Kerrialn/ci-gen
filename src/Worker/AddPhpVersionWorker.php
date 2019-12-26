<?php

declare(strict_types=1);

namespace CIConfigGen\Worker;

use CIConfigGen\Contract\WorkerInterface;

final class AddPhpVersionWorker implements WorkerInterface
{

    public function isMatch(array $array, string $level, string $expected): bool
    {
        return isset($array[$level][$expected]);
    }
}
