<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class GitlabGenerator implements GeneratorInterface {

    /**
     * @var string
     */
    private const SERVICE_NAME = 'Gitlab CI';
    private const SERVICE_FILE_PATH = '.gitlab-ci.yml';
    private const SERVICE_FILENAME = '.gitlab-ci.yml';

    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): array
    {
        $output = [
            'image' => 'php:latest',
            'stages' => [
                'test'
            ],
            'before_script:' => [
                'curl -sS https://getcomposer.org/installer | php',
                'php composer.phar install'
            ]
        ];

        $output['install'][] = 'composer install';

        if ($intermediaryObject->hasPhpUnitTests())
        {
            $output['phpUnit'] = [
                'stage' => 'test',
                'script' => ['vendor/bin/phpunit']
            ];
        }

        if ($intermediaryObject->hasEasyCodingStandards())
        {
            $output['eastCodingStandards'] = [
                'stage' => 'test',
                'script' => ['vendor/bin/ecs check --ansi']
            ];
        }

        if ($intermediaryObject->hasPhpStan())
        {
            $output['phpStan'] = [
                'stage' => 'test',
                'script' => ['vendor/bin/phpstan analyse --ansi']
            ];
        }

        return $output;
    }

    public function getName(): string
    {
        return self::SERVICE_NAME;
    }

    public function getFilename(): string
    {
        return self::SERVICE_FILENAME;
    }

    public function getPath(): string
    {
        return self::SERVICE_FILE_PATH;
    }
}
