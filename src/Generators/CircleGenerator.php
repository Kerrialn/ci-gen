<?php

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class CircleGenerator implements GeneratorsInterface
{

    /**
     * @var PHPUnitService
     */
    private $PHPUnitService;

    /**
     * CircleGenerator constructor.
     * @param PHPUnitService $PHPUnitService
     */
    public function __construct(PHPUnitService $PHPUnitService)
    {
        $this->PHPUnitService = $PHPUnitService;
    }

    public function isMatch(string $ciService): string
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
                'build' => [
                    'environment' => [],
                ],
                'script' => $this->PHPUnitService->create(),
                'deploy' => [],
            ],
        ];
    }
}
