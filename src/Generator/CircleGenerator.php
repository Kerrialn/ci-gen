<?php

declare(strict_types=1);

namespace CIConfigGen\Generator;

use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\ScriptFactory\PHPUnitScriptFactory;
use CIConfigGen\ValueObject\Constants;

final class CircleGenerator implements GeneratorInterface
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
                    'script' => $this->phpUnitScriptFactory->create(),
                ],
            ],
        ];
    }

    public function getFilename(): string
    {
        return '.circleci/config.yml';
    }
}
