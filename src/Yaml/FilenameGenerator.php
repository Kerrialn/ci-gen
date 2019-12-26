<?php

declare(strict_types=1);

namespace CIConfigGen\Yaml;

use CIConfigGen\ValueObject\Constants;

final class FilenameGenerator
{
    public function generateFilename(string $ciService): string
    {
        if ($ciService === Constants::GITLAB_CI) {
            $filename = '.gitlab-ci.yml';
        } elseif ($ciService === Constants::GITHUB_ACTIONS) {
            mkdir(getcwd() . '.github/workflows');
            $filename = '.github/workflows/continuous-integration-workflow.yml';
        } elseif ($ciService === Constants::BITBUCKET_CI) {
            $filename = 'bitbucket-pipelines.yml';
        } elseif ($ciService === Constants::TRAVIS_CI) {
            $filename = '.travis.yml';
        } elseif ($ciService === Constants::CIRCLE_CI) {
            mkdir(getcwd() . 'circleci');
            $filename = '.circleci/config.yml';
        } else {
            $filename = 'unknown-ci.yml';
        }

        return $filename;
    }
}
