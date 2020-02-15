<?php

declare(strict_types=1);

namespace CIConfigGen\Generator;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\ScriptFactory\PHPUnitScriptFactory;
use CIConfigGen\ValueObject\CiService;

final class GithubGenerator implements GeneratorInterface
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
        return $ciService === CiService::GITHUB_ACTIONS;
    }

    public function generate(array $composerJson): array
    {
        return [
            'name' => CiService::GITHUB_ACTIONS,
            'language' => 'PHP',
            'on' => 'push',
            'jobs' => [
                'build' => [
                    'steps' => [
                        'uses' => 'actions/checkout@v1',
                        [
                            'name' => 'test',
                            'php' => $composerJson['require']['php'],
                            'run' => $this->phpUnitScriptFactory->create(),
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getFilename(): string
    {
        return '.github/workflows/continuous-integration-workflow.yml';
    }
}
