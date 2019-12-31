<?php

declare(strict_types=1);

namespace CIConfigGen\Yaml;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\Exception\ShouldNotHappenException;

final class FilenameGenerator
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

    public function generateFilename(string $ciService): string
    {
        foreach ($this->generators as $generator) {
            if (! $generator->isMatch($ciService)) {
                continue;
            }

            return $generator->getFilename();
        }

        throw new ShouldNotHappenException();
    }
}
