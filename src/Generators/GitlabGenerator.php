<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class GitlabGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    private const SERVICE_NAME = 'Gitlab CI';
    private const SERVICE_FILE_PATH = '.gitlab-ci';
    private const SERVICE_OUTPUT_FORMAT = 'yaml';

    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): IntermediaryGenerateObject
    {
        $output = [
            'image' => 'php:latest',
            'stages' => [
                'test',
            ],
        ];

        if ($intermediaryObject->hasPhpUnitTests()) {
            $output['phpUnit'] = [
                'stage' => 'test',
                'script' => ['vendor/bin/phpunit'],
            ];
        }

        if ($intermediaryObject->hasEasyCodingStandards()) {
            $output['easyCodingStandards'] = [
                'stage' => 'test',
                'script' => ['vendor/bin/ecs check --ansi'],
            ];
        }

        if ($intermediaryObject->hasPhpStan()) {
            $output['phpStan'] = [
                'stage' => 'test',
                'script' => ['vendor/bin/phpstan analyse --ansi'],
            ];
        }

        $intermediaryObject->setFileContent($output);
        $intermediaryObject->setOutputFormat(self::SERVICE_OUTPUT_FORMAT);
        $intermediaryObject->setFilename( self::SERVICE_FILE_PATH . '.' . self::SERVICE_OUTPUT_FORMAT);
        return $intermediaryObject;
    }

    public function getName(): string
    {
        return self::SERVICE_NAME;
    }


    public function getPath(): string
    {
        return self::SERVICE_FILE_PATH;
    }

    public function getOutputFormat() : string
    {
        return self::SERVICE_OUTPUT_FORMAT;
    }
}
