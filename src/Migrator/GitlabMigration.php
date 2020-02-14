<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

use CIConfigGen\Contract\MigrateInterface;
use CIConfigGen\ValueObject\CiService;

final class GitlabMigration implements MigrateInterface
{
    public function isMatch(string $ciService): bool
    {
        return $ciService === CiService::GITLAB_CI;
    }

    public function migrate(array $tempArray, string $destination): array
    {
        // 1. push to array with 'Gitlab' pattern
        $output = [];
        $output['stages'] = [];

        if ($tempArray['install']) {
            if (strpos($tempArray['install'], 'composer') !== false) {
                $output['before_script'] = 'nothing';
            }
        }

        if ($tempArray['jobs']) {
            $output = $this->migrateJobs($tempArray, $output);
        }

        if ($tempArray['cache']['directories']) {
            $output['cache']['paths'] = $tempArray['cache']['directories'];
        }

        return $output;
    }

    public function migrateJobs(array $tempArray, array $output): array
    {
        foreach ($tempArray['jobs'] as $job) {
            if (! in_array($job['stage'], $output['stages'], true)) {
                $output['stages'][] = $job['stage'];
            }
        }

        foreach ($tempArray['jobs'] as $key => $job) {
            if (in_array($job['name'], $output, true)) {
                continue;
            }

            $output[$job['name'] ?: ($job['stage'] . '_' . $key)] = [
                'stage' => $job['stage'],
                'image' => 'php:' . $job['php'] ?? null,
                'script' => $job['script'],
            ];
        }
        return $output;
    }
}
