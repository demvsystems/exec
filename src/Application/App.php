<?php

namespace Demv\Exec\Application;

use Demv\Exec\Command;

class App 
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $args = [];

    /**
     * @var string
     */
    protected $input;

    /**
     * @var Command
     */
    protected $command;

    /**
     * Create a new Application with the command it belongs to and the application 
     * name
     * 
     * @param Command $command the command this application call belongs to
     * @param string  $name    the name of the application 
     */
    public function __construct(Command $command, string $name)
    {
        $this->command = $command;
        $this->name    = $name;
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
     * Return the name of the application
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Input for the application call. 
     *
     * @param string $input
     *
     * @return Application
     */
    public function input(string $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Returns the raw application call as a string
     *
     * @return string
     */
    public function getRaw()
    {
        return sprintf(
            '%s %s %s',
            $this->name,
            implode(' ', $this->args),
            $this->input
        );
    }

    /**
     * Adds an argument to the application call
     *
     * @param string $name  the argument name
     * @param string $value the value of the argument if any
     * @param string $pre   is the argument introduced by an - or --. Defaults to -
     *
     * @return Application
     */
    public function arg(string $name, $value = '', $pre = '-')
    {
        $arg = $pre . $name;
        if (!empty($value)) {
            $arg .= '=' . $value;
        }

        $this->args[] = $arg;

        return $this;
    }
}
