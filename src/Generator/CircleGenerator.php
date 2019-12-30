<?php

declare(strict_types=1);

namespace CIConfigGen\Generator;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class CircleGenerator implements GeneratorInterface
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
        return $ciService === Constants::CIRCLE_CI;
    }

    public function generate(array $composerJson): array
    {
        return [
            'name' => Constants::CIRCLE_CI,
            'language' => 'PHP',
            'on' => '[push]',
            'jobs' => [
                'test' => [
                    'name' => 'PhpUnit',
                    'script' => $this->phpUnitService->create(),
                ],
            ],
        ];
    }

    public function getFilename(): string
    {
        return '.circleci/config.yml';
    }
}
