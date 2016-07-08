<?php

use Demv\Exec\Command;

class YiiAppTest extends PHPUnit_Framework_TestCase
{
    public function testAlternateScript()
    {
        $script   = 'another_script.php';
        $expected = 'php ' . $script;
        $result   = Command::create()
            ->yiiApp()
            ->script($script)
            ->getRaw();

        $this->assertEquals($expected, $result);
    }

    public function testCmd()
    {
        $cmd = 'importer';

        $expected = 'php console.php ' . $cmd;

        $result = Command::create()
            ->yiiApp()
            ->cmd($cmd)
            ->getRaw();

        $this->assertEquals($expected, $result);
    }

    public function testCmdAndAction()
    {
        $cmd = 'meeting';
        $action = 'remind';
        $expected = sprintf('%s %s %s', 'php console.php', $cmd, $action);

        $result = Command::create()
            ->yiiApp()
            ->cmd($cmd)
            ->action($action)
            ->getRaw();

        $this->assertEquals($expected, $result);
    }

    public function testCmdAndArgs()
    {
        $cmd = 'import';
        $expected = 'php console.php ' . $cmd . ' --source=google --id=1';

        $result = Command::create()
            ->yiiApp()
            ->cmd($cmd)
            ->arg('source', 'google', '--')
            ->arg('id', '1', '--')
            ->getRaw();

        $this->assertEquals($expected, $result);
    }

    public function testCmdAndNonDoubleDashArgs()
    {
        $cmd = 'controller';
        $arg1 = 'param1';
        $arg2 = 'param2';
        $expected = sprintf(
            'php console.php %s %s=something %s=123',
            $cmd,
            $arg1,
            $arg2
        );

        $result = Command::create()
            ->yiiApp()
            ->cmd($cmd)
            ->arg($arg1, 'something', '')
            ->arg($arg2, '123', '')
            ->getRaw();

        $this->assertEquals($expected, $result);
    }

    public function testCmdActionAndArgs()
    {
        $cmd = 'controller';
        $action = 'action';
        $arg1 = 'begin';
        $arg2 = 'end';
        $expected = sprintf(
            'php console.php %s %s --%s=1 --%s=11000000',
            $cmd,
            $action,
            $arg1,
            $arg2
        );

        $result = Command::create()
            ->yiiApp()
            ->cmd($cmd)
            ->action($action)
            ->arg($arg1, '1', '--')
            ->arg($arg2, '11000000', '--')
            ->getRaw();

        $this->assertEquals($expected, $result);
    }
}
