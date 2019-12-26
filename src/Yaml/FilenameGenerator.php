<?php

declare(strict_types=1);

namespace CIConfigGen\Yaml;

final class FilenameGenerator {

    public function generateFilename(string $ciService): string
    {
        $filename = '';

        if ($ciService == 'GitlabCI')
        {
            $filename = ".gitlab-ci.yml";

        } else if ($ciService == 'TravisCI')
        {
            $filename = ".travis.yml";

        } else if ($ciService == 'CircleCI')
        {
            mkdir(getcwd() . 'circleci');
            $filename = ".circleci/config.yml";

        } else if ($ciService == 'JenkinsCI')
        {
            $filename = "jenkins.yml";

        } else
        {
            $filename = "unknown.yml";
        }

        return $filename;
    }

}
