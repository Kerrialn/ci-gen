<?php

declare(strict_types=1);

namespace CIConfigGen\Detector;

use CIConfigGen\ValueObject\CiService;
use Nette\Utils\Strings;
use Symfony\Component\Console\Style\SymfonyStyle;

class DetectCIFromGitConfig
{
    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }

    public function detect(array $filename): array
    {
        $ciServices = [];

        if (Strings::contains($filename['url'], 'github')) {
            $this->symfonyStyle->note('Github detected');
            $ciServices[] = CiService::GITHUB_ACTIONS;
            $ciServices[] = CiService::TRAVIS_CI;
            $ciServices[] = CiService::CIRCLE_CI;
        } elseif (Strings::contains($filename['url'], 'gitlab')) {
            $this->symfonyStyle->note('Gitlab detected');
            $ciServices[] = CiService::GITLAB_CI;
        } elseif (Strings::contains($filename['url'], 'bitbucket')) {
            $this->symfonyStyle->note('Bitbucket detected');
            $ciServices[] = CiService::BITBUCKET_CI;
        } else {
            $ciServices[] = CiService::GITLAB_CI;
            $ciServices[] = CiService::TRAVIS_CI;
            $ciServices[] = CiService::CIRCLE_CI;
            $ciServices[] = CiService::GITHUB_ACTIONS;
            $ciServices[] = CiService::TRAVIS_CI;
        }

        return $ciServices;
    }
}
