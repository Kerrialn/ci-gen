<?php

namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;
use Symplify\MonorepoBuilder\ComposerJsonObject\ComposerJsonFactory;

final class GeneratorSelector
{
    /**
     * @var string
     */
    private const COMPOSER_JSON_PATH = __DIR__ . '/../../composer.json';

    /**
     * @var GeneratorInterface[]
     */
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
                $this->composerJsonFactory->createFromFilePath(self::COMPOSER_JSON_PATH),
            );

            return $generator->generate($intermediaryObject);
        }

        throw new \RuntimeException('Could not generateFromComposerJson');
    }
}
