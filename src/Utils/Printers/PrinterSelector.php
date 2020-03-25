<?php


namespace App\Utils\Printers;

use App\Contracts\PrinterInterface;
use App\Intermediary\IntermediaryGenerateObject;

final class PrinterSelector
{
    private array $printers = [];

    /**
     *
     * @param PrinterInterface[] $printers
     */
    public function __construct(array $printers)
    {
        $this->printers = $printers;
    }

    public function generateFileFromObject(IntermediaryGenerateObject $intermediaryGenerateObject)
    {
        foreach ($this->printers as $printer) {
            if (!$printer->isMatch($intermediaryGenerateObject->getOutputFormat())) {
                continue;
            }
            return $printer->generate($intermediaryGenerateObject);
        }
    }
}
