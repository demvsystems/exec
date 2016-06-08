<?php

namespace Demv\Exec;

use Demv\Exec\Application\App;
use Demv\Exec\Application\AwkApp;
use Demv\Exec\Application\PhpApp;
use Demv\Exec\Application\YiiApp;
use Demv\Exec\Application\XargsApp;
use Demv\Exec\Exception\OsNoMatchException;
use Demv\Exec\Exception\OsNotSupportedExeception;
use Demv\Exec\Result\Result;

/**
 * The command is the highest unit, which consists of one or more application calls
 */
class Command
{
    /**
     * @var array
     */
    private $apps = [];

    /**
     * @var string
     */
    private $os = OS::ALL;

    /**
     * @var bool
     */
    private $async = false;

    /**
     * @var int
     */
    private $pid = 0;

    /**
     * Create a new command
     *
     * @return Command
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Private because it can only be created by the factory method 
     */
    private function __construct()
    {
    }


    /**
     * Execute the command
     *
     * @return Result
     */
    public function exec()
    {
        $this->checkOs();

        exec($this->getRaw(), $output);

        if ($this->async) {
            $this->pid = (int) $output;
        }

        return new Result($output);
    }

    /**
     * Checks if the set os is similar to the os of the executing machinge
     *
     * @throws OsNoMatchException
     */
    private function checkOs()
    {
        if ($this->os !== OS::ALL && $this->os !== OS::getCurrentOs()) {
            throw new OsNoMatchException();
        }
    }

    /**
     * Return the raw command as a string
     *
     * @return string
     */
    public function getRaw()
    {
        $command = implode(' | ', array_map(
            function (App $app) {
                return $app->getRaw();
            },
            $this->apps
        ));

        if ($this->async) {
            $current_os = OS::getCurrentOs();
            if ($current_os === OS::WIN) {
                //TODO: This is experimental and not tested
                $command = 'start ' . $command;
            } else {
                $command .= ' &> /dev/null & echo $!';
            }
        }

        return $command;
    }

    /**
     * Add a new app call to the command
     *
     * @param string $name the name of the app
     *
     * @return App
     */
    public function app(string $name)
    {
        $app          = new App($this, $name);
        $this->apps[] = $app;

        return $app;
    }

    /**
     * Set the OS restriction of the command
     *
     * @param string $os see the OS Enum for possibilities
     *
     * @return Command
     */
    public function setOS(string $os)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * Enable async for this command
     *
     * @return Command
     */
    public function async()
    {
        $this->async = true;

        return $this;
    }

    /**
     * Add a call to PHP 
     *
     * @return PhpApp
     */
    public function phpApp()
    {
        $phpApp = new PhpApp($this);

        $this->apps[] = $phpApp;

        return $phpApp;
    }

    /**
     * Add a call to Yii's console application
     *
     * @return YiiApp
     */
    public function yiiApp()
    {
        $yiiApp = new YiiApp($this);

        $this->apps[] = $yiiApp;

        return $yiiApp;
    }

    /**
     * Add a call Awk 
     *
     * @return AwkApp
     */
    public function awkApp()
    {
        $awkApp = new AwkApp($this);

        $this->apps[] = $awkApp;

        return $awkApp;
    }

    /**
     * Add a call to xargs
     *
     * @return XargsApp
     */
    public function xargsApp()
    {
        $xargsApp = new XargsApp($this);

        $this->apps[] = $xargsApp;

        return $xargsApp;
    }

    /**
     * Checks if the current command is running
     *
     * @return bool
     */
    public function isRunning()
    {
        if (OS::WIN === OS::getCurrentOs()) {
            throw new OsNotSupportedExeception();
        }

        $app = new App($this, 'ps');
        $app->arg('p')
            ->input($this->pid);
        
        exec($app->getRaw(), $output);

        //First line is the header. If line 1 exists, there is a process with this pid
        return array_key_exists(1, $output);
    }

    /**
     * Check if a command is running, which has the exact same syntax
     *
     * @return bool
     */
    public function isSimilarRunning()
    {
        if (OS::WIN === OS::getCurrentOs()) {
            throw new OsNotSupportedExeception();
        }

        $app = new App($this, 'ps');
        $app->arg('eF');

        $command = implode(' | ', array_map(
            function (App $app) {
                return $app->getRaw();
            },
            $this->apps
        ));

        exec($app->getRaw(), $output);
        $result = strpos(implode(PHP_EOL, $output), $this->prepareForPs($command));

        return $result ? true : false;
    }

    /**
     * Prepares a command to be searched in the process log
     *
     * @param string $command the command to be prepared
     *
     * @return string the prepared command
     */
    private function prepareForPs(string $command)
    {
        $command = preg_replace('#\'#', '', $command);

        return preg_replace('#"#', '', $command);
    }
}
