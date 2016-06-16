<?php

namespace Demv\Exec;

use Demv\Exec\Application\App;
use Demv\Exec\Exception\OsNotSupportedExeception;

final class Async
{
    /**
     * @var Command
     */
    private $command;

    /**
     * @var int
     */
    private $pid = 0;

    /**
     * @var string
     */
    private $path = '/dev/null';

    /**
     * Create an Async Object for the given command
     *
     * @param Command $command
     *
     * @return Async
     */
    public static function create(Command $command)
    {
        $async = new self($command);

        return $async;
    }

    /**
     * @param Command $command
     */
    private function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * Magic call method delegates a method call to the command it holds 
     * if it isn't a method of the application
     *
     * @param string $name the method name
     * @param array  $args the method parameters
     *
     * @return mixed
     */
    public function __call(/*string*/ $name, /*array*/ $args)
    {
        return call_user_func_array([$this->command, $name], $args);
    }

    /**
     * Get the pid of this command, if executed. Zero otherwise 
     *
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Executes it's command and saves the pid
     *
     * @return Result
     */
    public function exec()
    {
        exec($this->getRaw(), $output);
        $this->pid = (int) $output[0];

        return $this;
    }

    /**
     * Retrieves the raw content of the command and add the async functionality
     *
     * @return string
     */
    public function getRaw()
    {
        $command        = $this->command->getRaw();
        $current_os     = OS::getCurrentOs();
        if ($current_os === OS::WIN) {
            //TODO: This is experimental and not tested
            $command = 'start ' . $command;
        } else {
            $command .= ' > ' . $this->path . ' 2>&1 & echo $!';
        }

        return $command;
    }

    public function path(/*string*/ $path)
    {
        $this->path = $path;

        return $this;
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

        $result = Command::create()
            ->app('ps')
            ->input('aux')
            ->exec();

        $result = strpos(
            $result->getRaw(),
            $this->prepareForProcessLog($this->command->getRaw())
        );

        return $result ? true : false;
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

        $result = Command::create()
            ->app('ps')
            ->arg('p')
            ->input($this->pid)
            ->exec();

        //First line is the header. If line 1 exists, there is a process with this pid
        return array_key_exists(1, explode(PHP_EOL, $result->getRaw()));
    }


    /**
     * Prepares a command to be searched in the process log
     *
     * @param string $command the command to be prepared
     *
     * @return string the prepared command
     */
    private function prepareForProcessLog(/*string*/ $command)
    {
        $command = preg_replace('#\'#', '', $command);

        return preg_replace('#"#', '', $command);
    }
}
