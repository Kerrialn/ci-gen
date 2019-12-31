<?php

declare(strict_types=1);

namespace CIConfigGen\Generator;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\ScriptFactory\PHPUnitScriptFactory;
use CIConfigGen\ValueObject\CiService;

final class BitbucketGenerator implements GeneratorInterface
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
        return $ciService === CiService::BITBUCKET_CI;
    }

    public function generate(array $composerJson): array
    {
        return [
            'name' => CiService::BITBUCKET_CI,
            'language' => 'PHP',
            'on' => '[push]',
            'pipelines' => [
                'step' => [
                    'default' => [
                        'name' => 'test',
                        'php' => $composerJson['require']['php'],
                        'script' => $this->phpUnitScriptFactory->create(),
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
