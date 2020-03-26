<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class TravisGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    private const SERVICE_NAME = 'Travis CI';
    private const SERVICE_TYPE = 'ci';
    private const SERVICE_FILE_PATH = '.travis';
    private const SERVICE_OUTPUT_FORMAT = 'yml';


    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): IntermediaryGenerateObject
    {
        $output = [
            'language' => 'php',
            'required' => $intermediaryObject->getPhpVersion(),
        ];

        $output['install'][] = 'composer install';

        if ($intermediaryObject->hasPhpUnitTests()) {
            $output['jobs']['include'][] = [
                'name' => 'Php Unit',
                'stage' => 'test',
                'php' => $intermediaryObject->getPhpVersion(),
                'script' => 'vendor/bin/phpunit --testsuite main',
            ];
        }

        if ($intermediaryObject->hasEasyCodingStandards()) {
            $output['jobs']['include'][] = [
                'name' => 'Easy Coding Standards',
                'stage' => 'test',
                'php' => $intermediaryObject->getPhpVersion(),
                'script' => ['composer check-cs src', 'composer check-cs src -- --fix'],
            ];
        }

        if ($intermediaryObject->hasPhpStan()) {
            $output['jobs']['include'][] = [
                'name' => 'Php Stan',
                'stage' => 'test',
                'php' => $intermediaryObject->getPhpVersion(),
                'script' => 'vendor/bin/phpstan analyse --ansi',
            ];
        }

        if ($intermediaryObject->hasPhpAssumptions()) {
            $output['jobs']['include'][] = [
                'name' => 'Php Assumptions',
                'stage' => 'test',
                'php' => $intermediaryObject->getPhpVersion(),
                'script' => 'vendor/bin/phpa src',
            ];
        }

        $intermediaryObject->setFileContent($output);
        $intermediaryObject->setOutputFormat(self::SERVICE_OUTPUT_FORMAT);
        $intermediaryObject->setFilename(self::SERVICE_FILE_PATH . '.' . self::SERVICE_OUTPUT_FORMAT);
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

    public function getOutputFormat(): string
    {
        return self::SERVICE_OUTPUT_FORMAT;
    }

    public function getType(): string
    {
        return self::SERVICE_TYPE;
    }
}
