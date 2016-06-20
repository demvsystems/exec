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
}
