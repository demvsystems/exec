<?php

namespace Demv\Exec;

use Demv\Exec\Application\App;
use Demv\Exec\Application\AwkApp;
use Demv\Exec\Application\PhpApp;
use Demv\Exec\Application\YiiApp;
use Demv\Exec\Exception\OsNoMatchException;
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

        return new Result($output);
    }

    /**
     * Checks if the set os is similar to the os of the executing machinge
     *
     * @throws OsNoMatchException
     */
    private function checkOs()
    {
        if ($this->os !== OS::ALL && $this->os !== strtoupper(substr(PHP_OS, 0, 3))) {
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
        return implode(' | ', array_map(
            function (App $app) {
                return $app->getRaw();
            },
            $this->apps
        ));
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
}
