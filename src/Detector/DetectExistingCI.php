<?php


namespace CIConfigGen\Detector;


use CIConfigGen\ValueObject\Constants;
use Symfony\Component\Console\Style\SymfonyStyle;

class DetectExistingCI {

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }

    public function detect(): array
    {
        foreach(Constants::CI_SERVICES as $key => $value){
            if(file_exists($value)){
                return array($key => $value);
            }
        }
    }
}
