<?php

declare(strict_types=1);

namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;
use App\Utils\ComposerJsonFactory;

final class GeneratorSelector
{
    private array $generators = [];
    private ComposerJsonFactory $composerJsonFactory;

    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $generators,
        ComposerJsonFactory $composerJsonFactory
    ) {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->generators = $generators;
    }


    public function generateFromComposerJson(string $ciService): IntermediaryGenerateObject
    {
        foreach ($this->generators as $generator) {
            if (! $generator->isMatch($ciService)) {
                continue;
            }

            $intermediaryObject = new IntermediaryGenerateObject(
                $generator->getName(),
                $this->composerJsonFactory->getComposerJson(),
            );

            return $generator->generate($intermediaryObject);
        }

        throw new \RuntimeException('Could not generateFromComposerJson');
    }
}
