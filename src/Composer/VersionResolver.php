<?php

declare(strict_types=1);

namespace CIConfigGen\Composer;

use Nette\Utils\Strings;
use PharIo\Version\Version;

final class VersionResolver
{
    /**
     * @return Version[]
     */
    public function resolvePhpVersions(array $composerJson): array
    {
        $minimalPhpVersion = $composerJson['require']['php'];

        $matches = Strings::match($minimalPhpVersion, '#(?<version>\d\.\d)#');
        $currentPhpVersion = new Version($matches['version']);

        $php74Version = new Version('7.4');

        $phpVersions = [];
        $phpVersions[] = $currentPhpVersion;

        while ($php74Version->isGreaterThan($currentPhpVersion)) {
            $nextVersion = $this->getNextMinorVersion($currentPhpVersion);
            $currentPhpVersion = new Version($nextVersion);

            $phpVersions[] = $currentPhpVersion;
        }

        return $phpVersions;
    }

    private function getNextMinorVersion(Version $version): string
    {
        return $version->getMajor()->getValue() . '.' . ($version->getMinor()->getValue() + 1);
    }
}
