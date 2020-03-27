<?php

declare(strict_types=1);

namespace App\Intermediary;

use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;

final class IntermediaryGenerateObject
{
    private ?string $service;
    private ComposerJson $composerJson;
    private array $fileContent;
    private string $outputFormat;
    private string $filename;

    public function __construct(?string $name, ComposerJson $composerJson)
    {
        $this->service = $name;
        $this->composerJson = $composerJson;
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function getFileContent(): array
    {
        return $this->fileContent;
    }

    public function getOutputFormat(): string
    {
        return $this->outputFormat;
    }

    public function setFileContent($fileContent): void
    {
        $this->fileContent = $fileContent;
    }

    public function setOutputFormat($outputFormat): void
    {
        $this->outputFormat = $outputFormat;
    }

    public function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getPhpVersion(): ?string
    {
        return trim($this->composerJson->getRequire()['php'], '^');
    }

    public function hasEasyCodingStandards(): bool
    {
        return $this->composerJson->hasPackage('symplify/easy-coding-standard');
    }

    public function hasPhpStan(): bool
    {
        return $this->composerJson->hasPackage('phpstan/phpstan');
    }

    public function hasPhpUnitTests()
    {
        return $this->composerJson->hasPackage('phpunit/phpunit');
    }

    public function hasPhpAssumptions()
    {
        return $this->composerJson->hasPackage('rskuipers/php-assumptions');
    }

    public function hasPackage(string $package): bool
    {
        return $this->composerJson->hasPackage($package);
    }
}
