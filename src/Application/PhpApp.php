<?php

namespace Demv\Exec\Application;

use Demv\Exec\Command;

/**
 * PHP Application call 
 */
class PhpApp extends App
{
    /**
     * @var string
     */
    protected $name = 'php';


    /**
     * Create a new PHP Application callwith the command it belongs to 
     * 
     * @param Command $command the command this application call belongs to
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
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
}
