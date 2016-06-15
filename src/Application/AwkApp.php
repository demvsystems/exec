<?php

namespace Demv\Exec\Application;

final class AwkApp extends App
{
    /**
     * Create a new Awk Application call with the command or application it belongs 
     * to 
     * 
     * @param Command|App $parent the command or application this application call 
     * belongs to
     */
    public function __construct($parent)
    {
        parent::__construct($parent, 'awk');
    }

    /**
     * Magic call method delegates a method call to the application or command it 
     * belongs to if it isn't a method of the application
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
     * Source code for the awk call. surrounding quotes and curlies will be provided 
     * and don't need to be included in the input
     *
     * @param string $input
     *
     * @return Application
     */
    public function input(/*string*/ $input)
    {
        $this->input = sprintf('%s%s%s',  '\'{', $input, '}\'');

        return $this;
    }
}
