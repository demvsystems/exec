<?php

namespace Demv\Exec\Application;

use Demv\Exec\Command;

class AwkApp extends App
{
    /**
     * Create a new Awk Application callwith the command it belongs to 
     * 
     * @param Command $command the command this application call belongs to
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
        $this->name    = 'awk';
    }

    /**
     * Magic call method delegates a method call to the command it belongs to
     * if it isn't a method of the application
     *
     * @param string $name the method name
     * @param array  $args the method parameters
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        return call_user_func_array([$this->command, $name], $args);
    }

    /**
     * Source code for the awk call. surrounding quotes and curlies will be provided 
     * and don't need to be included in the input
     *
     * @param string $input
     *
     * @return Application
     */
    public function input(string $input)
    {
        $this->input = sprintf('%s%s%s',  '\'{', $input, '}\'');

        return $this;
    }
}
