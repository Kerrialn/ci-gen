<?php


namespace App\Generators;


use App\Contracts\GeneratorInterface;
use App\Intermediary\IntermediaryObject;
use Symplify\MonorepoBuilder\ComposerJsonObject\ValueObject\ComposerJson;

class GithubActionsGenerator implements GeneratorInterface {

    private const SERVICE_NAME = 'GithubActions';

    public function isMatch(string $service_name): bool
    {
        return $service_name === self::SERVICE_NAME;
    }

    public function generate(ComposerJson $composerJson): array
    {
       $intermediaryObject = new IntermediaryObject();

        return [
           'name' => $intermediaryObject->getName()
       ];

    }

}