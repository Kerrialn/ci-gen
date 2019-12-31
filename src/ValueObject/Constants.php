<?php

namespace CIConfigGen\ValueObject;

/**
 * Class Constants
 * @package CIConfigGen\ValueObject
 */
class Constants {

    /**
     * @var array
     */
    public const CI_SERVICES = [
        self::CIRCLE_CI => '.circleci/config.yml',
        self::TRAVIS_CI => '.travis.yml',
        self::GITHUB_ACTIONS => '.github/workflows/continuous-integration-workflow.yml',
        self::GITLAB_CI => '.gitlab-ci.yml',
        self::BITBUCKET_CI => 'bitbucket-pipelines.yml'
    ];

    /**
     * @var string
     */
    public const CIRCLE_CI = 'Circle CI';

    /**
     * @var string
     */
    public const CIRCLE_CI_FILENAME = 'config.yml';

    /**
     * @var string
     */
    public const CIRCLE_CI_FILE_PATH = '.circleci/config.yml';

    /**
     * @var string
     */
    public const TRAVIS_CI = 'Travis CI';

    /**
     * @var string
     */
    public const TRAVIS_CI_FILENAME = '.travis.yml';

    /**
     * @var string
     */
    public const TRAVIS_CI_FILE_PATH = '.travis.yml';

    /**
     * @var string
     */
    public const GITHUB_ACTIONS = 'Github Actions';

    /**
     * @var string
     */
    public const GITHUB_ACTIONS_FILENAME = 'continuous-integration-workflow.yml';

    /**
     * @var string
     */
    public const GITHUB_ACTIONS_FILE_PATH = '.github/workflows/continuous-integration-workflow.yml';

    /**
     * @var string
     */
    public const GITLAB_CI = 'Gitlab CI';

    /**
     * @var string
     */
    public const GITLAB_CI_FILENAME = '.gitlab-ci.yml';

    /**
     * @var string
     */
    public const GITLAB_CI_FILE_PATH = '.gitlab-ci.yml';

    /**
     * @var string
     */
    public const BITBUCKET_CI = 'Bitbucket CI';

    /**
     * @var string
     */
    public const BITBUCKET_CI_FILENAME = 'bitbucket-pipelines.yml';

    /**
     * @var string
     */
    public const BITBUCKET_CI_FILE_PATH = 'bitbucket-pipelines.yml';
}
