<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class JenkinsGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    private const SERVICE_NAME = 'Jenkins CI';
    private const SERVICE_TYPE = 'ci';
    private const SERVICE_FILE_PATH = '.woloxci/config';
    private const SERVICE_OUTPUT_FORMAT = 'yml';


    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): IntermediaryGenerateObject
    {
        $output = [
            'config' => [
                'project_name' => 'Jenkins CI Project',
                'language' => 'php',
                'version' => $intermediaryObject->getPhpVersion(),
            ],
        ];

        if ($intermediaryObject->hasPhpUnitTests()) {
            $output['jobs']['steps']['phpUnit'][] = 'vendor/bin/phpunit --testsuite main';
        }

        if ($intermediaryObject->hasEasyCodingStandards()) {
            $output['jobs']['steps']['easy-coding-standards'][] = 'composer check-cs src';
            $output['jobs']['steps']['easy-coding-standards'][] = 'composer check-cs src -- --fix';
        }

        if ($intermediaryObject->hasPhpStan()) {
            $output['jobs']['steps']['phpstan'][] = 'vendor/bin/phpstan analyse --ansi';
        }

        if ($intermediaryObject->hasPhpAssumptions())
        {
            $output['jobs']['steps']['phpAssumptions'][] = 'vendor/bin/phpa src';
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
