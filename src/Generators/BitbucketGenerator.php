<?php

declare(strict_types=1);

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class BitbucketGenerator implements GeneratorInterface
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
        return $ciService === Constants::BITBUCKET_CI;
    }

    public function generate(array $composerJson): array
    {
        return [
            'name' => Constants::BITBUCKET_CI,
            'language' => 'PHP',
            'on' => '[push]',
            'pipelines' => [
                'step' => [
                    'default' => [
                        'name' => 'test',
                        'php' => $composerJson['require']['php'],
                        'script' => $this->phpUnitService->create(),
                    ],
                ],
            ],
        ];
    }

    public function getFilename(): string
    {
        return 'bitbucket-pipelines.yml';
    }
}
