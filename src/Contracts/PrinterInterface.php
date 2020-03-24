<?php

declare(strict_types=1);

namespace App\Contracts;


use App\Intermediary\IntermediaryGenerateObject;

interface PrinterInterface {

    public function isMatch(string $outputFormat): bool;

    public function convertFileContent(array $fileContent) : string;

    public function generate(IntermediaryGenerateObject $intermediaryGenerateObject): void;

}