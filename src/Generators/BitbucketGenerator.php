<?php

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\ValueObject\Constants;

class BitbucketGenerator implements GeneratorsInterface {

    public function isMatch(string $ciService): string
    {
        return $ciService === Constants::BITBUCKET_CI;
    }

    public function generate(array $composerJson): array
    {


        return array(
            'name' => Constants::BITBUCKET_CI,
            'language' => 'PHP',
            'on' => '[push]',
            'pipelines' => [
                'step' => [
                    'default' => [
                        'name' => 'Build and test',
                        'php' => $composerJson['require']['php'],
                        'script' => [
                            '- composer install',

                        ]
                    ]
                ]
            ]
        );
    }
}
