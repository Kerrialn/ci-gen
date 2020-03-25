<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Intermediary\IntermediaryGenerateObject;

interface GeneratorInterface
{
    public function getName(): string;

    public function getPath(): string;

    public function isMatch(string $ciService): bool;

    public function generate(IntermediaryGenerateObject $intermediaryObject): IntermediaryGenerateObject;
}
