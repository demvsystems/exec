<?php

namespace Demv\Exec\Application;

/**
 * Yii Application call
 */
final class YiiApp extends App
{
    /**
     * @var string
     */
    private $script = 'console.php';

    /**
     * @var string
     */
    private $cmd;

    /**
     * @var string
     */
    private $action;

    /**
     * Create a new Yii Application call with the command or application it belongs 
     * to 
     * 
     * @param Command|App $parent the command or application this application call 
     *                            belongs to
     */
    public function __construct($parent)
    {
        parent::__construct($parent, 'php');
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
     * Set the script
     *
     * @param string $script the script to be set
     *
     * @return YiiApp
     */
    public function script(/*string*/ $script)
    {
        $this->script = $script;

        return $this;
    }

    /**
     * Set the Yii Command
     *
     * @param string $cmd the cmd to be set
     *
     * @return YiiApp
     */
    public function cmd(/*string*/ $cmd)
    {
        $this->cmd = $cmd;

        return $this;
    }

    /**
     * Set the action
     *
     * @param string $action the action to be set
     *
     * @return YiiApp
     */
    public function action(/*string*/ $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Returns the raw application call as a string.
     *
     * @return string
     */
    public function getRaw()
    {
        $raw = sprintf('%s %s', $this->name, $this->script);

        if (!empty($this->cmd)) {
            $raw = sprintf('%s %s', $raw, $this->cmd);
        }
        if (!empty($this->action)) {
            $raw = sprintf('%s %s', $raw, $this->action);
        }
        if (!empty($this->args)) {
            $raw = sprintf('%s %s', $raw, implode(' ', $this->args));
        }
        if (!empty($this->input)) {
            $raw = sprintf('%s %s', $raw, $this->input);
        }

        return $raw;
    }
}
