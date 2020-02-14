<?php

declare(strict_types=1);

namespace CIConfigGen\Migrator;

class tempObject
{
    public $name;

    public $install;

    public $language;

    public $on;

    public $image;

    public $services;

    public $stages;

    public $jobs;

    public $types;

    public $before;

    public $after;

    public $variables;

    public $cache;

    final public function convert(array $array): self
    {
        $object = new self();

        if ($array['install']) {
            $object->install = $array['install'];
        }

        if ($array['name']) {
            $object->name = $array['name'];
        }

        if ($array['language']) {
            $object->language = $array['language'];
        }
        if ($array['on']) {
            $object->on = $array['on'];
        }
        if ($array['image']) {
            $object->image = $array['image'];
        }
        if ($array['services']) {
            $object->services = $array['services'];
        }
        if ($array['stages']) {
            $object->stages = $array['stages'];
        }

        if ($array['jobs']) {
            $this->convertJobs($array, $object);
        }

        if ($array['before_script']) {
            $object->before = $array['before_script'];
        }
        if ($array['after_script']) {
            $object->after = $array['after_script'];
        }
        if ($array['variables']) {
            $object->variables = $array['variables'];
        }

        if ($array['cache']) {
            $object->cache = $array['cache'];
        }

        return $object;
    }

    final public function toArray()
    {
        return (array) $this;
    }

    public function convertJobs(array $array, self $object): void
    {
        if ($array['jobs']['include']) {
            $object->jobs = $array['jobs']['include'];
        }

        if ($array['jobs']['build']) {
            if ($array['job']['build']['steps']) {
                $object->jobs = $array['jobs']['build']['steps'];
            }
        }

        if ($array['jobs'] && ! $array['jobs']['include']) {
            $object->jobs = $array['jobs'];
        }
    }
}
