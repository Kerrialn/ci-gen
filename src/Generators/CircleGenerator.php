<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class CircleGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    private const SERVICE_NAME = 'Circle CI';
    private const SERVICE_TYPE = 'ci';
    private const SERVICE_FILE_PATH = '.circleci/config';
    private const SERVICE_OUTPUT_FORMAT = 'yml';


    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): IntermediaryGenerateObject
    {
        $output = [
            'version' => 2,
        ];

        if ($intermediaryObject->hasPhpUnitTests()) {
            $output['jobs']['test']['steps'][] = [
                'name' => 'Php Unit',
                'command' => 'vendor/bin/phpunit --testsuite main',
            ];
        }

        if ($intermediaryObject->hasEasyCodingStandards()) {
            $output['jobs']['test']['steps'][] = [
                'name' => 'Easy Coding Standards',
                'php' => $intermediaryObject->getPhpVersion(),
                'command' => ['composer check-cs src', 'composer check-cs src -- --fix'],
            ];
        }

        if ($intermediaryObject->hasPhpStan()) {
            $output['jobs']['test']['steps'][] = [
                'name' => 'Php Stan',
                'command' => 'vendor/bin/phpstan analyse --ansi',
            ];
        }

        if ($intermediaryObject->hasPhpAssumptions()) {
            $output['jobs']['test']['steps'][] = [
                'name' => 'Php Assumptions',
                'command' => 'vendor/bin/phpa src',
            ];
        }


        $output['workflows'] = [
            'version' => 2,
            'build_and_test' => [
                'jobs' => [
                    'test',
                ],
            ],
        ];

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
