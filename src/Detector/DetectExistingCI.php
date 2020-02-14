<?php

declare(strict_types=1);

namespace CIConfigGen\Detector;

use CIConfigGen\ValueObject\Constants;

class DetectExistingCI
{
    public function detect(): array
    {
        $array = [];
        foreach (Constants::CI_SERVICES as $key => $value) {
            if (file_exists($value)) {
                $array = [$key => $value];
            }
        }
        return $array;
    }
}
