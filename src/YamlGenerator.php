<?php

declare(strict_types=1);

namespace CIConfigGen;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\Contract\MigrateInterface;

final class YamlGenerator
{
    private $migrators = [];

    /**
     * @var GeneratorInterface[]
     */
    private $generators = [];

    /**
     * @param GeneratorInterface[] $generators
     * @param MigrateInterface[] $migrators
     */
    public function __construct(array $generators, array $migrators)
    {
        $this->generators = $generators;
        $this->migrators = $migrators;
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

    public function migrateFromObject(array $intermediaryArray, string $ciService): array
    {
        foreach ($this->migrators as $migrate) {
            if (! $migrate->isMatch($ciService)) {
                continue;
            }

            return $migrate->migrate($intermediaryArray, $ciService);
        }

        return [];
    }
}
