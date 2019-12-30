<?php

namespace CIConfigGen\Generators;

use CIConfigGen\Contract\GeneratorsInterface;
use CIConfigGen\Services\PHPUnitService;
use CIConfigGen\ValueObject\Constants;

final class TravisGenerator implements GeneratorsInterface
{
    /**
     * @var PHPUnitService
     */
    private $phpUnitService;

    /**
     * TravisGenerator constructor.
     */
    public function __construct(PHPUnitService $phpUnitService)
    {
        $this->phpUnitService = $phpUnitService;
    }

    public function isMatch(string $ciService): string
    {
        return $ciService === Constants::TRAVIS_CI;
    }

    public function generate(array $composerJson): array
    {
        return [
            'name' => Constants::TRAVIS_CI,
            'php' => $composerJson['require']['php'],
            'install' => ['composer update --prefer-source $COMPOSER_FLAGS'],
            'test' => [
                'name' => 'PhpUnit',
                'php' => $composerJson['require']['php'],
                'script' => $this->phpUnitService->create(),
            ],
            'cache'=>[
                'directories'=> ['$HOME/.composer/cache']
            ],
            'notifications' => [
                'email' => false,
            ],
        ];
    }
}
