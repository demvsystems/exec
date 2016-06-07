<?php

use Demv\Exec\Command;

class AppTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test adding an argument for an app
     */
    public function testArg()
    {
        $input    = '"Hello\nWorld"';
        $expected = 'Hello' . PHP_EOL . 'World';
        $result   = Command::create()
            ->app('echo')
            ->arg('e')
            ->input($input)
            ->exec();

        $this->assertEquals($expected, $result->getRaw());
    }
}
