<?php

declare(strict_types=1);

namespace CIConfigGen\Yaml;

use CIConfigGen\ValueObject\Constants;

final class FilenameGenerator
{
    public function generateFilename(string $ciService): string
    {
        if ($ciService === Constants::GITLAB_CI) {
            $filename = Constants::GITLAB_CI_FILE_PATH;
        } elseif ($ciService === Constants::GITHUB_ACTIONS) {
            mkdir(getcwd() . '.github/workflows');
            $filename = Constants::GITHUB_ACTIONS_FILE_PATH;
        } elseif ($ciService === Constants::BITBUCKET_CI) {
            $filename = Constants::BITBUCKET_CI_FILE_PATH;
        } elseif ($ciService === Constants::TRAVIS_CI) {
            $filename = Constants::TRAVIS_CI_FILE_PATH;
        } elseif ($ciService === Constants::CIRCLE_CI) {
            mkdir(getcwd() . 'circleci');
            $filename = Constants::CIRCLE_CI_FILE_PATH;
        } else {
            $filename = 'unknown-ci.yml';
        }

        return $filename;
    }
}
