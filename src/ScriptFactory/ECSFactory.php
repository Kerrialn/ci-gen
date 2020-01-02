<?php

declare(strict_types=1);

namespace CIConfigGen\ScriptFactory;

class ECSFactory
{
    public function create(array $composerJson): array
    {
        $ecs = [];

        if ($composerJson['require-dev']['symplify/easy-coding-standard']) {
            if ($composerJson['scripts']['check-cs']) {
                $ecs[] = 'composer check-cs src';
                $ecs[] = 'composer check-cs src -- --fix';
            }
        }
        return $ecs;
    }
}
