<?php

namespace Demv\Exec\Application;

/**
 * PHP Application call 
 */
final class PhpApp extends App
{
    /**
     * Create a new PHP Application call with the command or application it belongs 
     * to 
     * 
     * @param Command|App $parent the command or application this application call 
     * belongs to
     */
    public function __construct($parent)
    {
        parent::__construct($parent, 'php');
    }

    /**
     * Magic call method delegates a method call to the command or application 
     * it belongs to if it isn't a method of the application
     *
     * @param string $name the method name
     * @param array  $args the method parameters
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        return call_user_func_array([$this->parent, $name], $args);
    }
}
