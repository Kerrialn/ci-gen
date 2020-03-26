<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class GithubActionsGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    private const SERVICE_NAME = 'Github Actions';
    private const SERVICE_TYPE = 'ci';
    private const SERVICE_FILE_PATH = '.github/workflows/continuous-integration-workflow';
    private const SERVICE_OUTPUT_FORMAT = 'yml';

    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): IntermediaryGenerateObject
    {
        $output = [
            'name' => $intermediaryObject->getService(),
            'on' => [ "pull_request" => null, 'push' => ['branches' => "master"]],
        ];

        if ($intermediaryObject->hasEasyCodingStandards()) {
            $output['jobs']['easy-coding-standards']['name'] = 'Easy Coding Standards detected';
            $output['jobs']['easy-coding-standards']['runs-on'] = 'ubuntu-latest';
            $output['jobs']['easy-coding-standards']['steps'] = [
                ['uses' => 'actions/checkout@v2'],
                ['uses' => 'shivammathur/setup-php@v1', 'with' => ['php-version' => $intermediaryObject->getPhpVersion(), 'coverage' => 'none']],
                ['run' => 'composer install --no-progress'],
                ['run' => 'vendor/bin/ecs check --ansi'],
            ];
        }

        if ($intermediaryObject->hasPhpStan()) {
            $output['jobs']['phpstan']['name'] = 'Php stan';
            $output['jobs']['phpstan']['runs-on'] = 'ubuntu-latest';
            $output['jobs']['phpstan']['steps'] = [
                ['uses' => 'actions/checkout@v2'],
                ['uses' => 'shivammathur/setup-php@v1', 'with' => ['php-version' => $intermediaryObject->getPhpVersion(), 'coverage' => 'none', 'tools' => 'cs2pr']],
                ['run' => 'composer install --no-progress'],
                ['run' => 'vendor/bin/phpstan analyse --ansi'],
            ];
        }

        if ($intermediaryObject->hasPhpUnitTests()) {
            $output['jobs']['phpunit']['name'] = 'Php Unit';
            $output['jobs']['phpunit']['runs-on'] = 'ubuntu-latest';
            $output['jobs']['phpunit']['steps'] = [
                ['uses' => 'actions/checkout@v2'],
                ['uses' => 'shivammathur/setup-php@v1', 'with' => ['php-version' => $intermediaryObject->getPhpVersion(), 'coverage' => 'none']],
                ['run' => 'composer install --no-progress'],
                ['run' => 'vendor/bin/phpunit'],
            ];
        }


        if ($intermediaryObject->hasPhpAssumptions())
        {
            $output['jobs']['phpAssumptions']['name'] = 'Php Assumptions';
            $output['jobs']['phpAssumptions']['runs-on'] = 'ubuntu-latest';
            $output['jobs']['phpAssumptions']['steps'] = [
                ['run' => 'vendor/bin/phpa src'],
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
