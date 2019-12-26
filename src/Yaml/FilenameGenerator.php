<?php

declare(strict_types=1);

namespace CIConfigGen\Yaml;

final class FilenameGenerator {

    public function generateFilename(string $ciService): string
    {
        if ($ciService == 'GitlabCI')
        {
            $filename = ".gitlab-ci.yml";

        } else if ($ciService == 'GithubActions')
        {
            mkdir(getcwd() . '.github/workflows');
            $filename = ".github/workflows/continuous-integration-workflow.yml";

        } else if ($ciService == 'BitbucketCI')
        {
            $filename = "bitbucket-pipelines.yml";

        } else if ($ciService == 'TravisCI')
        {
            $filename = ".travis.yml";

        } else if ($ciService == 'CircleCI')
        {
            mkdir(getcwd() . 'circleci');
            $filename = ".circleci/config.yml";

        } else
        {
            $filename = "unknown-ci.yml";
        }

        return $filename;
    }

}
