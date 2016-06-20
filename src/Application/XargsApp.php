<?php 

namespace Demv\Exec\Application;

final class XargsApp extends App {

    /**
     * @var App
     */
    private $app1 = null;

    /**
     * @var App
     */
    private $app2 = null;

    /**
     * Create a new Awk Application callwith the command or application 
     * it belongs to 
     * 
     * @param Command|App $parent the command or application this application call 
     * belongs to
     */
    public function __construct($parent)
    {
        parent::__construct($parent, 'xargs');
    }

    /**
     * Magic call method delegates a method call to the command or application it
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
     * Receives the names of two applications. The first will be applied to the 
     * second application
     *
     * @param string $input
     *
     * @return Application
     */
    public function input(/*string*/ $app1, $app2 = '')
    {
        $this->app1 = new App($this, $app1);
        $this->app2 = new App($this, $app2);

        return $this;
    }

    /**
     * Returns the raw xargs call as a string
     *
     * @return string
     */
    public function getRaw()
    {
        $args = '';
        foreach ($this->args as $arg) {
            $args = sprintf('%s %s', $args, $arg->getRaw());
        }

        return sprintf(
            '%s | %s %s %s',
            $this->app1->getRaw(),
            $this->name,
            $args,
            $this->app2->getRaw()
        );
    }

    /**
     * Retrieve the first app
     *
     * @return App
     */
    public function app1()
    {
        return $this->app1;
    }

    /**
     * Retrieve the second app
     *
     * @return App
     */
    public function app2()
    {
        return $this->app2;
    }
}
