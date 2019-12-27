<?php

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class GitlabGenerator implements GeneratorsInterface
{
    public function isMatch(string $ciService): string
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
                'script' => (new PHPUnitService())->create(),
            ],
        ];
    }
}
