<?php

declare(strict_types=1);

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class GithubGenerator implements GeneratorInterface
{
    /**
     * @var PHPUnitService
     */
    private $phpUnitService;

    public function __construct(PHPUnitService $phpUnitService)
    {
        $this->phpUnitService = $phpUnitService;
    }

    public function isMatch(string $ciService): bool
    {
        return $ciService === Constants::GITHUB_ACTIONS;
    }

    public function generate(array $composerJson): array
    {
        return [
            'name' => Constants::GITHUB_ACTIONS,
            'language' => 'PHP',
            'on' => '[push]',
            'jobs' => [
                'build' => [
                    'steps' => [
                        'uses' => 'actions/checkout@v1',
                        [
                            'name' => 'test',
                            'php' => $composerJson['require']['php'],
                            'run' => $this->phpUnitService->create(),
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
