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
            ->arg('n')
            ->input($input)
            ->exec();

        $this->assertEquals($expected, $result->getRaw());
    }

    /**
     * Test the getRaw method by just giving an app name
     */
    public function testGetRaw()
    {
        $expected = 'ls';
        $result = Command::create()
            ->app('ls')
            ->getRaw();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test the getRaw method by giving an app name and an input
     */
    public function testGetRawWithInput()
    {
        $expected = 'echo Hallo Welt';
        $result = Command::create()
            ->app('echo')
            ->input('Hallo Welt')
            ->getRaw();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test the getRaw method by giving an app name and arguments 
     */
    public function testGetRawWithArgs()
    {
        $expected = 'ls -a --block-size=M';
        $result = Command::create()
            ->app('ls')
            ->arg('a')
            ->arg('block-size', 'M', '--')
            ->getRaw();

        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test the getRaw method by giving an app name, an argument and an input
     */
    public function testGetRawWithArgsAndInput()
    {
        $expected = 'echo -e Hallo Welt';
        $result = Command::create()
            ->app('echo')
            ->arg('e')
            ->input('Hallo Welt')
            ->getRaw();

        $this->assertEquals($expected, $result);
    }
}
