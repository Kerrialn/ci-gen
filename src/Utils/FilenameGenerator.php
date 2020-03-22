<?php


namespace App\Utils;


use App\Contracts\GeneratorInterface;
use App\Exception\ShouldNotHappenException;

final class FilenameGenerator
{
    /**
     * @var GeneratorInterface[]
     */
    private array $generators = [];

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

            return $generator->getPath();
        }

        throw new \RuntimeException('Could not generate file name');
    }
}