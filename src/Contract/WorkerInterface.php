<?php

declare(strict_types=1);

namespace CIConfigGen\Contract;

interface WorkerInterface
{
    public function isMatch(array $array, string $level, string $expected): bool;
}
