<?php

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\ValueObject\Constants;

final class GithubGenerator implements GeneratorsInterface
{
    public function isMatch(string $ciService): string
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
                    'runs-on' => 'ubuntu-latest',
                    'steps' => [
                        'uses' => 'actions/checkout@v1',
                        [
                            'name' => 'test',
                            'php' => $composerJson['require']['php'],
                            'run' => (new PHPUnitService())->create(),
                        ],
                    ],
                ],
            ],
        ];
    }
}
