<?php

declare(strict_types=1);

namespace CIConfigGen\Generator;

use CIConfigGen\Composer\VersionResolver;
use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\ScriptFactory\ECSFactory;
use CIConfigGen\ScriptFactory\PHPUnitScriptFactory;
use CIConfigGen\ValueObject\CiService;

final class TravisGenerator implements GeneratorInterface
{
    /**
     * @var PHPUnitScriptFactory
     */
    private $phpUnitScriptFactory;

    /**
     * @var VersionResolver
     */
    private $versionResolver;

    /**
     * @var ECSFactory
     */
    private $ecsFactory;

    public function __construct(
        PHPUnitScriptFactory $phpUnitScriptFactory,
        VersionResolver $versionResolver,
        ECSFactory $ecsFactory
    ) {
        $this->phpUnitScriptFactory = $phpUnitScriptFactory;
        $this->versionResolver = $versionResolver;
        $this->ecsFactory = $ecsFactory;
    }

    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::TRAVIS_CI;
    }

    public function generate(array $composerJson): array
    {
        $phpVersions = $this->versionResolver->resolvePhpVersions($composerJson);

        $yaml['language'] = 'php';

        $yaml['install'][] = 'composer install';

        $phpunitJob = $this->phpUnitScriptFactory->create();
        if ($phpunitJob) {
            foreach ($phpVersions as $phpVersion) {
                $yaml['jobs']['include'][] = [
                    'stage' => 'test',
                    'php' => $phpVersion->getVersionString(),
                    'script' => $phpunitJob,
                ];
            }
        }

        $ecsJob = $this->ecsFactory->create($composerJson);
        if ($ecsJob) {
            foreach ($phpVersions as $phpVersion) {
                $yaml['jobs']['include'][] = [
                    'stage' => 'test',
                    'name' => 'ECS',
                    'php' => $phpVersion->getMajor(),
                    'script' => $ecsJob,
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
