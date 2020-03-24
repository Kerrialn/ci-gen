<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;
use App\Utils\ComposerJsonFactory;
use Symplify\MonorepoBuilder\ComposerJsonObject\ValueObject\ComposerJson;

final class GeneratorSelector
{
    private const COMPOSER_JSON_PATH = __DIR__ . '/../../composer.json';
    private array $generators = [];
    private ComposerJsonFactory $composerJsonFactory;

    /**
     * @param GeneratorInterface[] $generators
     * @param ComposerJsonFactory $composerJsonFactory
     */
    public function __construct(array $generators,
                                ComposerJsonFactory
                                $composerJsonFactory)
    {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->generators = $generators;
    }

    public function loadComposerJson(): ComposerJson
    {
        return $this->composerJsonFactory->createFromFilePath(self::COMPOSER_JSON_PATH);
    }

    public function generateFromComposerJson(string $ciService): IntermediaryGenerateObject
    {
        foreach ($this->generators as $generator) {
            if (!$generator->isMatch($ciService))
            {
                continue;
            }

            $intermediaryObject = new IntermediaryGenerateObject(
                $generator->getName(),
                $this->loadComposerJson(),
            );

            return $generator->generate($intermediaryObject);
        }

        throw new \RuntimeException('Could not generateFromComposerJson');

    }
}
