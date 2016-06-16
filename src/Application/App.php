<?php

namespace Demv\Exec\Application;

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
     * @var Command|App
     */
    protected $parent;

    /**
     * Create a new Application with the parent it belongs to and the application 
     * name.
     * 
     * @param Command|App $parent the parent application or command of this 
     *                            application call belongs to
     * @param string      $name   the name of the application 
     */
    public function __construct($parent, /*string*/ $name)
    {
        $this->parent = $parent;
        $this->name = $name;
    }

    /**
     * Magic call method delegates a method call to the parent it belongs to
     * if it isn't a method of the application.
     *
     * @param string $name the method name
     * @param array  $args the method parameters
     *
     * @return mixed
     */
    public function __call(/*string*/ $name, /*array*/ $args)
    {
        return call_user_func_array([$this->parent, $name], $args);
    }

    /**
     * Return the name of the application.
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
     * @return App
     */
    public function input(/*string*/ $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Returns the raw application call as a string.
     *
     * @return string
     */
    public function getRaw()
    {
        $raw = $this->name;
        if (!empty($this->args)) {
            $raw = sprintf('%s %s', $raw, implode(' ', $this->args));
        }
        if (!empty($this->input)) {
            $raw = sprintf('%s %s', $raw, $this->input);
        }

        return $raw;
    }

    /**
     * Adds an argument to the application call.
     *
     * @param string $name  the argument name
     * @param string $value the value of the argument if any
     * @param string $pre   is the argument introduced by an - or --. Defaults to -
     *
     * @return App
     */
    public function arg(/*string*/ $name, $value = '', $pre = '-')
    {
        $arg = $pre.$name;
        if (!empty($value)) {
            $arg .= '='.$value;
        }

        $this->args[] = $arg;

        return $this;
    }
}
