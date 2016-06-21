<?php

use Demv\Exec\Command;

class AsyncTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test an async call
     */
    public function testAsync()
    {
        $async = Command::create()
            ->app('echo')
            ->input('"Hello World"')
            ->async()
            ->exec();

        //Because of async we are only awaiting the pid
        $this->assertGreaterThan(0, (int) $async->getPid());
    }

    /**
     * Test if the async command is still running
     */
    public function testIsRunning()
    {
        $async = Command::create()
            ->phpApp()
            ->arg('r')
            ->input('\'sleep(10);\'')
            ->async()
            ->exec();

        $this->assertTrue($async->isRunning());
        sleep(11);
        $this->assertFalse($async->isRunning());
    }

    /**
     * Test if a Command with the same syntax is running
     */
    public function testIsSimilarRunning()
    {
        $async = Command::create()
            ->phpApp()
            ->arg('r')
            ->input('\'sleep(10);\'')
            ->async()
            ->exec();

        $this->assertTrue($async->isSimilarRunning());
        sleep(10);
        $this->assertFalse($async->isSimilarRunning());
    }

    /**
     * Test if async output is written to alternative paths
     */
    public function testPath()
    {
        $path = 'test.txt';
        $expected = 'Hallo Welt';

        $async = Command::create()
            ->app('echo')
            ->input($expected)
            ->async()
            ->path($path)
            ->exec();

        $this->assertFileExists($path);
        $this->assertEquals($expected, trim(file_get_contents($path)));
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function testKill()
    {
        $async = Command::create()
            ->phpApp()
            ->arg('r')
            ->input('\'sleep(10);\'')
            ->async()
            ->exec();
        $this->assertTrue($async->isRunning());
        $async->kill();
        $this->assertFalse($async->isRunning());
    }

    public function testKillSimilar()
    {
        $async = Command::create()
            ->phpApp()
            ->arg('r')
            ->input('\'sleep(10);\'')
            ->async()
            ->exec();
        $this->assertTrue($async->isSimilarRunning());
        $async->killSimilar();
        $this->assertFalse($async->isSimilarRunning());
    }
}
