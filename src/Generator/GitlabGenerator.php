<?php

declare(strict_types=1);

namespace CIConfigGen\Generator;

use CIConfigGen\Composer\VersionResolver;
use CIConfigGen\Contract\GeneratorInterface;
use CIConfigGen\ScriptFactory\ECSFactory;
use CIConfigGen\ScriptFactory\PHPUnitScriptFactory;
use CIConfigGen\ValueObject\CiService;

final class GitlabGenerator implements GeneratorInterface
{
    private $ecsFactory;

    private $versionResolver;

    /**
     * @var PHPUnitScriptFactory
     */
    private $phpUnitScriptFactory;

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
        return $ciService === CiService::GITLAB_CI;
    }

    public function generate(array $composerJson): array
    {
        $yaml = [];

        $phpunitJob = $this->phpUnitScriptFactory->create();
        $ecsJob = $this->ecsFactory->create($composerJson);

        if ($composerJson) {
            $yaml['stages'][] = 'install';
        }
        if ($phpunitJob || $ecsJob) {
            $yaml['stages'][] = 'test';
        }
        if ($this->versionResolver->getMinimalVersion($composerJson)) {
            $yaml['stages'][] = 'build';
        }

        if ($composerJson) {
            $yaml['install'] = [
                'stage' => 'install',
                'script' => ['composer verify', 'composer install'],
            ];
        }

        if ($phpunitJob) {
            $yaml['phpunit_jobs'] = [
                'stage' => 'test',
                'script' => $phpunitJob,
            ];
        }

        if ($ecsJob) {
            $yaml['ecs_jobs'] = [
                'stage' => 'test',
                'script' => $ecsJob,
            ];
        }

        return $yaml;
    }

    public function getFilename(): string
    {
        return '.gitlab-ci.yml';
    }
}
