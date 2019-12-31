<?php

declare(strict_types=1);

namespace CIConfigGen\ValueObject;

final class CiService
{
    /**
     * @var string
     */
    public const CIRCLE_CI = 'Circle CI';

    /**
     * @var string
     */
    public const TRAVIS_CI = 'Travis CI';

    /**
     * @var string
     */
    public const GITHUB_ACTIONS = 'Github Actions';

    /**
     * @var string
     */
    public const GITLAB_CI = 'Gitlab CI';

    /**
     * @var string
     */
    public const BITBUCKET_CI = 'Bitbucket CI';
}
