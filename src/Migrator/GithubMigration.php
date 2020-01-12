<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

use CIConfigGen\ValueObject\CiService;

class GithubMigration {

    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::GITLAB_CI;
    }

    public function migrate(MigrationIntermediaryObject $MigrationIntermediaryObject, string $destination): array
    {
        // 1. push to array with 'Github' pattern
        $output = [];

        if ($MigrationIntermediaryObject->jobs)
        {
            $output['stages'] = [];

            foreach ($MigrationIntermediaryObject->jobs as $include)
            {
                if (!in_array($include['stage'], $output['stages'], true))
                {
                    $output['stages'][] = $include['stage'];
                }
            }
        }

        if ($MigrationIntermediaryObject->language)
        {
            $output['language'] = $MigrationIntermediaryObject['language'];
        }

        return $output;
    }

}
