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
    private $phpUnitService;

    /**
     * CircleGenerator constructor.
     */
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
}
