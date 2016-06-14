<?php

use Demv\Exec\Command;
use Demv\Exec\Exception\OsNoMatchException;
use Demv\Exec\OS;

class CommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test a simple app call
     */
    public function testExec()
    {
        $input = 'Hello World';

        $result = Command::create()
            ->app('echo')
            ->input($input)
            ->exec();

        $this->assertEquals($input, $result->getRaw());
    }
    
    /**
     * Test the OS restriction of the command
     */
    public function testCorrectOs()
    {
        $input  = 'Hello World';
        $os     = strtoupper(substr(PHP_OS, 0, 3));
        $result = Command::create()
            ->setOS($os)
            ->app('echo')
            ->input($input)
            ->exec();
        $this->assertEquals($input, $result->getRaw());
    }

    /**
     * Test the OS restriction of the command
     * @expectedException Demv\Exec\Exception\OsNoMatchException
     */
    public function testWrongOs()
    {
        $input    = 'Hello World';
        $os       = strtoupper(substr(PHP_OS, 0, 3));
        $wrong_os = $os === OS::WIN ? OS::LINUX : OS::WIN;
        $result   = Command::create()
            ->setOS($wrong_os)
            ->app('echo')
            ->input($input)
            ->exec();
    }

    
    /**
     * Test piping of 2 applications
     */
    public function testPipe()
    {
        $input = [
            'orange',
            'banana',
            'cherry',
        ];

        $result = Command::create()
            ->app('echo')
            ->input('"' . implode(PHP_EOL, $input) . '"')
            ->app('sort')
            ->exec();
        
        sort($input);
        $this->assertEquals(implode($input, PHP_EOL), $result->getRaw());
    }

    /**
     * Test a PHP application call
     */
    public function testPhpApp()
    {
        $result = Command::create()
            ->phpApp()
            ->arg('v')
            ->exec();

        $this->assertEquals('PHP', substr($result->getRaw(), 0, 3));
    }

    /**
     * Test an awk application call
     */
    public function testAwkApp()
    {
        $input    = 'foo bar';
        $expected = 'bar';

        $result = Command::create()
            ->app('echo')
            ->input($input)
            ->awkApp()
            ->input('print $2;')
            ->exec();

        $this->assertEquals($expected, $result->getRaw());
    }

    /**
     * Test an xargs application call
     */
    public function testXargsApp()
    {
        $expected = 'foo' . PHP_EOL . 'bar' . PHP_EOL . 'baz' . PHP_EOL;
        $result   = Command::create()
            ->xargsApp()
            ->input('echo', 'echo')
            ->arg('d,')
            ->arg('L')
            ->arg('1', '', '')
            ->app1()
            ->input('foo,bar,baz')
            ->exec();
        $this->assertEquals($expected, $result->getRaw());
    }
}
