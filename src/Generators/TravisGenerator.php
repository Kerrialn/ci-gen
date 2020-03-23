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
    private const SERVICE_FILE_PATH = '.travis.yml';
    private const SERVICE_FILENAME = '.travis.yml';

    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): array
    {
        $output = [
            'name' => $intermediaryObject->getService(),
            'required' => $intermediaryObject->getPhpVersion(),
        ];

        if ($intermediaryObject->has("phpunit/phpunit")) {
            $output['tests'] = 'PHP Unit detected';
        }

        if ($intermediaryObject->hasEasyCodingStandards()) {
            $output['easy-coding-standards'] = 'Easy Coding Standards detected';
        }

        if ($intermediaryObject->hasPhpStan()) {
            $output['php-stan'] = 'PHP Stan detected';
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
