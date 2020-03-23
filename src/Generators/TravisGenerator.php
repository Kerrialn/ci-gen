<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class TravisGenerator implements GeneratorInterface {

    /**
     * @var string
     */
    private const SERVICE_NAME = 'Travis CI';
    private const SERVICE_FILE_PATH = '.travis.yml';
    private const SERVICE_FILENAME = '.travis.yml';

    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): array
    {
        $output = [
            'language' => 'php',
            'required' => $intermediaryObject->getPhpVersion(),
        ];

        $output['install'][] = 'composer install';

        if ($intermediaryObject->hasPhpUnitTests())
        {
            $output['jobs']['include'][] = [
                'name' => 'Php Unit',
                'stage' => 'test',
                'php' => $intermediaryObject->getPhpVersion(),
                'script' => 'vendor/bin/phpunit --testsuite main',
            ];
        }

        if ($intermediaryObject->hasEasyCodingStandards())
        {
            $output['jobs']['include'][] = [
                'name' => 'Easy Coding Standards',
                'stage' => 'test',
                'php' => $intermediaryObject->getPhpVersion(),
                'script' => ['composer check-cs src','composer check-cs src -- --fix']
            ];
        }

        if ($intermediaryObject->hasPhpStan())
        {
            $output['jobs']['include'][] = [
                'name' => 'Php Stan',
                'stage' => 'test',
                'php' => $intermediaryObject->getPhpVersion(),
                'script' => 'vendor/bin/phpstan analyse --ansi'
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
