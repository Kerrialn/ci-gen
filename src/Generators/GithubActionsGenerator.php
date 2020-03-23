<?php


namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class GithubActionsGenerator implements GeneratorInterface {

    /**
     * @var string
     */
    private const SERVICE_NAME = 'Github Actions';
    private const SERVICE_FILE_PATH = '.github/workflows/continuous-integration-workflow.yml';
    private const SERVICE_FILENAME = 'continuous-integration-workflow.yml';

    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): array
    {
        $output = [
            'name' => $intermediaryObject->getService(),
            'php' => $intermediaryObject->getPhpVersion(),
            'on' => [ "pull_request" => null, 'push' => ['branches' => "master"]],
            'jobs' => []
        ];

        if ($intermediaryObject->has("phpunit/phpunit"))
        {
            $output['jobs']['PhpUnit']['name'] = 'PHP Unit detected';
        }

        if ($intermediaryObject->hasEasyCodingStandards())
        {
            $output['jobs']['easy-coding-standards']['name']  = 'Easy Coding Standards detected';
        }

        if ($intermediaryObject->hasPhpStan())
        {
            $output['jobs']['php-stan']['name'] = 'PHP Stan detected';
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
