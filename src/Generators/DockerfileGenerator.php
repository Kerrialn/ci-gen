<?php

declare(strict_types=1);

namespace App\Generators;

use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class DockerfileGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    private const SERVICE_NAME = 'Dockerfile';
    private const SERVICE_TYPE = 'docker';
    private const SERVICE_FILE_PATH = 'Dockerfile';
    private const SERVICE_OUTPUT_FORMAT = 'text';

    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(IntermediaryGenerateObject $intermediaryObject): IntermediaryGenerateObject
    {
        $output = [
            'FROM' => $intermediaryObject->getPhpVersion(),
            'WORKDIR' => '/DOCKER_APPLICATION',
            'RUN' => 'apt-get update && apt-get install -y \ git \ unzip \ g++ \ libzip-dev \ && pecl -q install \ zip \ && docker-php-ext-configure \ opcache --enable-opcache \ && docker-php-ext-enable \ zip \ opcache',
        ];

        $intermediaryObject->setFileContent($output);
        $intermediaryObject->setOutputFormat(self::SERVICE_OUTPUT_FORMAT);
        $intermediaryObject->setFilename(self::SERVICE_FILE_PATH);
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
