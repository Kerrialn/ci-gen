<?php

declare(strict_types=1);

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;
use Nette\Utils\Strings;
use PharIo\Version\Version;

final class TravisGenerator implements GeneratorsInterface
{
    /**
     * @var PHPUnitService
     */
    private $phpUnitService;

    public function __construct(PHPUnitService $phpUnitService)
    {
        $this->phpUnitService = $phpUnitService;
    }

    public function isMatch(string $ciService): bool
    {
        return $ciService === Constants::TRAVIS_CI;
    }

    public function generate(array $composerJson): array
    {
        $phpVersions = $this->detectPhpVersionsFromComposerJson($composerJson);

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

    /**
     * @return Version[]
     */
    private function detectPhpVersionsFromComposerJson(array $composerJson): array
    {
        $minimalPhpVersion = $composerJson['require']['php'];

        $matches = Strings::match($minimalPhpVersion, '#(?<version>\d\.\d)#');
        $currentPhpVersion = new Version($matches['version']);

        $php74Version = new Version('7.4');

        $phpVersions = [];
        $phpVersions[] = $currentPhpVersion;

        while ($php74Version->isGreaterThan($currentPhpVersion)) {
            $nextVersion = $currentPhpVersion->getMajor()->getValue() . '.' . ($currentPhpVersion->getMinor()->getValue() + 1);
            $currentPhpVersion = new Version($nextVersion);
            $phpVersions[] = $currentPhpVersion;
        }

        return $phpVersions;
    }
}
