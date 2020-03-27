<?php

namespace App\Utils\Printers;

use App\Contracts\PrinterInterface;
use App\Intermediary\IntermediaryGenerateObject;
use Nette\Utils\FileSystem;

final class TextPrinter implements PrinterInterface
{
    private const PRINTER_OUTPUT = 'text';


    public function isMatch(string $output): bool
    {
        return $output === self::PRINTER_OUTPUT;
    }

    public function generate(IntermediaryGenerateObject $intermediaryGenerateObject): void
    {
        FileSystem::write($intermediaryGenerateObject->getFilename(), $this->convertFileContent($intermediaryGenerateObject->getFileContent()));
    }

    public function convertFileContent(array $fileContent): string
    {
        $string = '';
        foreach ($fileContent as $key => $value) {
            $string .= $key . ' ' . $value . PHP_EOL . PHP_EOL;
        }

        $exp = explode('\\', $string);

        return implode('\\ ' . PHP_EOL . '   ', $exp);
    }
}
