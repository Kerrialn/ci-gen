<?php

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class BitbucketGenerator implements GeneratorsInterface
{

    /**
     * @var PHPUnitService
     */
    private $PHPUnitService;


    /**
     * BitbucketGenerator constructor.
     * @param PHPUnitService $PHPUnitService
     */
    public function __construct(PHPUnitService $PHPUnitService)
    {
        $this->PHPUnitService = $PHPUnitService;
    }

    public function isMatch(string $ciService): string
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
                        'script' => $this->PHPUnitService->create(),
                    ],
                ],
            ],
        ];
    }
}
