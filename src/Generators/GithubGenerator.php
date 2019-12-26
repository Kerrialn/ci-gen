<?php


namespace CIConfigGen\Generators;


use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\ValueObject\Constants;

class GeneratorImplementation implements GeneratorsInterface {

    public function isMatch(string $ciService): string
    {
        return $ciService === Constants::GITHUB_ACTIONS;
    }


    public function generate(array $composerJson): array
    {
        return array([
            'name' => 'PHP Composer',
            'language' => 'PHP',
            'on' => '[push]',
            'jobs' => [
                'build' => [
                    'runs-on' => 'ubuntu-latest',
                    'steps' => [
                        'uses' => 'actions/checkout@v1',
                        [
                            'name' => 'Validate composer.json and composer.lock',
                            'run' => 'composer validate'
                        ],
                        [
                            'name' => 'composer validate',
                            'run' => 'composer install --prefer-dist --no-progress --no-suggest'
                        ]
                    ]
                ]
            ]
        ]);
    }
}
