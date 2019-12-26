<?php

declare(strict_types=1);

namespace CIConfigGen;

use CIConfigGen\ValueObject\Constants;
use CIConfigGen\Yaml\BitbucketYamlGenerator;
use CIConfigGen\Yaml\GithubYamlGenerator;
use CIConfigGen\Yaml\GitlabYamlGenerator;
use Symfony\Component\Console\Input\InputArgument;

final class YamlGenerator {

    /**
     * @var GithubYamlGenerator
     */
    private $GitHubGenerator;
    /**
     * @var GitlabYamlGenerator
     */
    private $GitlabYamlGenerator;
    /**
     * @var BitbucketYamlGenerator
     */
    private $BitbucketYamlGenerator;

    /**
     * @param GithubYamlGenerator $GitHubGenerator
     * @param GitlabYamlGenerator $GitlabYamlGenerator
     * @param BitbucketYamlGenerator $bitbucketYamlGenerator
     */
    public function __construct(
        GithubYamlGenerator $GitHubGenerator,
        GitlabYamlGenerator $GitlabYamlGenerator,
        BitbucketYamlGenerator $BitbucketYamlGenerator
    )
    {
        $this->GitHubGenerator = $GitHubGenerator;
        $this->GitlabYamlGenerator = $GitlabYamlGenerator;
        $this->BitbucketYamlGenerator = $BitbucketYamlGenerator;
    }

    public function generateFromComposerJson(array $composerJson, string $ciService): array
    {
        if ($ciService == Constants::GITLAB_CI)
        {
            $ciYaml = $this->GitlabYamlGenerator->Generate($composerJson);

        } else if ($ciService == Constants::GITHUB_ACTIONS)
        {
            $ciYaml = $this->GitHubGenerator->Generate($composerJson);

        } else if ($ciService == Constants::BITBUCKET_CI)
        {
            $ciYaml = $this->BitbucketYamlGenerator->Generate($composerJson);

        } else if ($ciService == Constants::TRAVIS_CI)
        {
            $ciYaml = $this->GitHubGenerator->Generate($composerJson);

        } else if ($ciService == Constants::CIRCLE_CI)
        {
            $ciYaml = $this->GitHubGenerator->Generate($composerJson);

        } else
        {
            $ciYaml = $this->GitHubGenerator->Generate($composerJson);
        }

        return $ciYaml;
    }
}
