<?php

declare(strict_types=1);

namespace CIConfigGen\Generator;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\ScriptFactory\PHPUnitScriptFactory;
use CIConfigGen\ValueObject\CiService;

final class GitlabGenerator implements GeneratorInterface
{
    /**
     * @var PHPUnitScriptFactory
     */
    private $phpUnitScriptFactory;

    public function __construct(PHPUnitScriptFactory $phpUnitScriptFactory)
    {
        $this->phpUnitScriptFactory = $phpUnitScriptFactory;
    }

    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::GITLAB_CI;
    }

    public function generate(array $composerJson): array
    {
        return [
            'name' => CiService::GITLAB_CI,
            'language' => 'PHP',
            'stages' => ['test'],

            'job' => [
                'stage' => 'test',
                'php' => $composerJson['require']['php'],
                'script' => $this->phpUnitScriptFactory->create(),
            ],
        ];
    }

    public function getFilename(): string
    {
        return '.gitlab-ci.yml';
    }
}
