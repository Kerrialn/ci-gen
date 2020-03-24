<?php


namespace App\Utils\Printers;

use App\Contracts\PrinterInterface;
use App\Intermediary\IntermediaryGenerateObject;
use App\Utils\FilenameGenerator;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Symfony\Component\Yaml\Yaml;
use function _HumbugBox3ab8cff0fda0\iter\rewindable\slice;

final class TextPrinter implements PrinterInterface {

    private const PRINTER_OUTPUT = 'text';
    private FilenameGenerator $filenameGenerator;

    /**
     *
     * @param $filenameGenerator
     */
    public function __construct(FilenameGenerator $filenameGenerator)
    {
        $this->filenameGenerator = $filenameGenerator;
    }

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
        foreach ($fileContent as $key => $value)
        {
            $string .= $key . ' ' . $value . PHP_EOL . PHP_EOL;
        }

        $exp = explode("\\", $string);

        $implo = implode('\\ '.PHP_EOL . '   ', $exp);

        return $implo;
    }


}
