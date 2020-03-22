<?php
declare(strict_types=1);

namespace App\Contracts;

use Symplify\MonorepoBuilder\ComposerJsonObject\ValueObject\ComposerJson;

interface GeneratorInterface {

    public function getName(): string;

    public function isMatch(string $ciService): bool;

    public function generate(ComposerJson $composerJson): array;

}