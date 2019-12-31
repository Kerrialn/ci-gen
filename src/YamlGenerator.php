<?php

declare(strict_types=1);

namespace CIConfigGen;

use CIConfigGen\Contract\GeneratorInterface;

final class YamlGenerator
{
    /**
     * @var GeneratorInterface[]
     */
    private $generators = [];

    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(array $generators)
    {
        $this->generators = $generators;
    }

    public function generateFromComposerJson(array $composerJson, string $ciService): array
    {
        foreach ($this->generators as $generator) {
            if (! $generator->isMatch($ciService)) {
                continue;
            }

            return $generator->generate($composerJson);
        }

        return [];
    }
}
