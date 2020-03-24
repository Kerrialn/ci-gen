<?php


namespace App\Intermediary;

use Symplify\MonorepoBuilder\ComposerJsonObject\ValueObject\ComposerJson;

final class IntermediaryGenerateObject {

    private ?string $service;
    private ComposerJson $composerJson;
    private array $fileContent;
    private string $outputFormat;
    private string $filename;

    /**
     *
     * @param string|null $name
     * @param ComposerJson $composerJson
     */
    public function __construct(?string $name, ComposerJson $composerJson)
    {
        $this->service = $name;
        $this->composerJson = $composerJson;
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @param string $service
     */
    public function setService(string $service): void
    {
        $this->service = $service;
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
        return $this->has('symplify/easy-coding-standard');
    }

    public function hasPhpStan(): bool
    {
        return $this->has('phpstan/phpstan');
    }

    public function hasPhpUnitTests()
    {
        return $this->has('phpunit/phpunit');
    }

    public function has(string $string): bool
    {
        return $this->checkRequire($string) == true || $this->checkDevRequire($string) == true ?: false;
    }

    public function checkRequire(string $string)
    {
        foreach ($this->composerJson->getRequire() as $key => $value)
        {
            if ($key != $string)
            {
                continue;
            }
            return true;
        }
    }

    public function checkDevRequire(string $string)
    {
        foreach ($this->composerJson->getRequireDev() as $key => $value)
        {
            if ($key != $string)
            {
                continue;
            }
            return true;
        }
    }

    public function checkFramework(string $sting): string
    {
        if ($this->has('symfony/framework-bundle'))
        {
            return 'symfony/framework-bundle';
        }
        if ($this->has('laravel/framework'))
        {
            return 'laravel/framework';
        }
        if ($this->has('nette/framework'))
        {
            return 'nette/framework';
        }

        return '';
    }

}
