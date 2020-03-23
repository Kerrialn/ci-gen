<?php

namespace App\Tests\Console\Command;

use App\Console\Command\GenerateCommand;
use PHPUnit\Framework\TestCase;

class GenerateCommandTest extends TestCase {

    /**
     * @dataProvider provider
     * @param $data
     */
    public function testMethod($data)
    {
        $this->assertTrue($data);
    }

    public function provider()
    {
        return [
            'my named data' => [true],
            'my data'       => [true]
        ];
    }

}
