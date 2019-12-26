<?php

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\ValueObject\Constants;

class GitlabGenerator implements GeneratorsInterface {

    public function isMatch(string $ciService): string
    {
        return $ciService === Constants::GITLAB_CI;
    }

    public function generate(array $composerJson): array
    {
        return array(
            'name'=>Constants::GITLAB_CI,
            'language' => 'PHP',
            'stages' => [
                'test',
            ],

            'job' => [
                'stage' => 'build',
                'php'=>$composerJson['require']['php'],
                'script' => [
                    ' - php -v',
                    '- composer install --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts',
                    'cp .env.example .env'
                ]
            ]
        );
    }
}
