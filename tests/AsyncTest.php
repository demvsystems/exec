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
    }
}
