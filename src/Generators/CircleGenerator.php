<?php

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\ValueObject\Constants;

final class CircleGenerator implements GeneratorsInterface
{
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
                'test' => [],
                'deploy' => [],
            ],
        ];
    }
}
