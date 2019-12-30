<?php

namespace CIConfigGen\ValueObject;

/**
 * Class Constants
 * @package CIConfigGen\ValueObject
 */
class Constants
{
    /**
     * @var string
     */
    public const CIRCLE_CI = 'Circle CI';
    public const CIRCLE_CI_FILENAME = 'config.yml';
    public const CIRCLE_CI_FILE_PATH = '.circleci/config.yml';

    /**
     * @var string
     */
    public const TRAVIS_CI = 'Travis CI';
    public const TRAVIS_CI_FILENAME = '.travis.yml';
    public const TRAVIS_CI_FILE_PATH = '.travis.yml';

    /**
     * @var string
     */
    public const GITHUB_ACTIONS = 'Github Actions';
    public const GITHUB_ACTIONS_FILENAME = 'continuous-integration-workflow.yml';
    public const GITHUB_ACTIONS_FILE_PATH = '.github/workflows/continuous-integration-workflow.yml';


    /**
     * @var string
     */
    public const GITLAB_CI = 'Gitlab CI';
    public const GITLAB_CI_FILENAME = '.gitlab-ci.yml';
    public const GITLAB_CI_FILE_PATH = '.gitlab-ci.yml';

    /**
     * @var string
     */
    public const BITBUCKET_CI = 'Bitbucket CI';
    public const BITBUCKET_CI_FILENAME = 'bitbucket-pipelines.yml';
    public const BITBUCKET_CI_FILE_PATH = 'bitbucket-pipelines.yml';





}
