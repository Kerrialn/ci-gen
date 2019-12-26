<?php

declare(strict_types=1);

namespace CIConfigGen;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\ValueObject\Constants;
use CIConfigGen\Yaml\BitbucketYamlGenerator;
use CIConfigGen\Yaml\GithubYamlGenerator;
use CIConfigGen\Yaml\GitlabYamlGenerator;
use Symfony\Component\Console\Input\InputArgument;

final class YamlGenerator {

    private $generators = [];

    /**
     * YamlGenerator constructor.
     * @param GeneratorsInterface[] $generators
     */
    public function __construct(array $generators)
    {
        $this->generators = $generators;
    }

    public function generateFromComposerJson(array $composerJson, string $ciService): array
    {
        foreach ($this->generators as $generator)
        {
            if ($generator->isMatch($ciService))
            {
                return $generator->generate($composerJson);
            }
        }
    }
}
