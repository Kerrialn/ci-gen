<?php

declare(strict_types=1);

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class GitlabGenerator implements GeneratorInterface
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
        return $ciService === Constants::GITLAB_CI;
    }

    public function generate(array $composerJson): array
    {
        return [
            'name' => Constants::GITLAB_CI,
            'language' => 'PHP',
            'stages' => ['test'],

            'job' => [
                'stage' => 'test',
                'php' => $composerJson['require']['php'],
                'script' => $this->phpUnitService->create(),
            ],
        ];
    }

    public function getFilename(): string
    {
        return '.gitlab-ci.yml';
    }
}
