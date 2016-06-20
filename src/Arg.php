<?php

namespace Demv\Exec;

class Arg
{
    /**
     * @var string
     */
    private $pre;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $name;

    public function __construct()
    {
    }

    public function setName(/*string*/ $name)
    {
        $this->name = $name;
    }

    public function setValue(/*string*/ $value)
    {
        $this->value = $value;
    }

    public function setPre(/*string*/ $pre)
    {
        $this->pre = $pre;
    }

    public function getRaw()
    {
        $raw = sprintf('%s%s', $this->pre, $this->name);
        if (!empty($this->value)) {
            switch ($this->pre) {
                case '-':
                    $raw .= ' ' . $this->value;
                    break;
                default:
                    $raw .= '=' . $this->value;
                    break;
            }
        }

        return $raw;
    }
}
