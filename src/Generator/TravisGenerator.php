<?php

declare(strict_types=1);

namespace CIConfigGen\Generator;

use CIConfigGen\Composer\VersionResolver;
use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class TravisGenerator implements GeneratorInterface
{
    /**
     * @var PHPUnitService
     */
    private $phpUnitService;

    /**
     * @var VersionResolver
     */
    private $versionResolver;

    public function __construct(PHPUnitService $phpUnitService, VersionResolver $versionResolver)
    {
        $this->phpUnitService = $phpUnitService;
        $this->versionResolver = $versionResolver;
    }

    public function isMatch(string $ciService): bool
    {
        return $ciService === Constants::TRAVIS_CI;
    }

    public function generate(array $composerJson): array
    {
        $phpVersions = $this->versionResolver->resolvePhpVersions($composerJson);

        $yaml['language'] = 'php';

        $yaml['install'][] = 'composer install';

        $phpunitJob = $this->phpUnitService->create();
        if ($phpunitJob) {
            foreach ($phpVersions as $phpVersion) {
                $yaml['jobs']['include'][] = [
                    'stage' => 'test',
                    'php' => $phpVersion->getVersionString(),
                    'script' => $phpunitJob,
                ];
            }
        }

        $yaml['cache']['directories'] = ['$HOME/.composer/cache'];
        $yaml['notifications']['email'] = false;

        return $yaml;
    }

    public function getFilename(): string
    {
        return '.travis.yml';
    }
}
