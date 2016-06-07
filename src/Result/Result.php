<?php

namespace Demv\Exec\Result;

class Result
{
    private $raw = [];

    /**
     * Construct a result
     *
     * @param array $raw lines of a result as an array
     */
    public function __construct(array $raw)
    {
        $this->raw = $raw;
    }

    /**
     * Retrieve the raw content as a string
     *
     * @return string
     */
    public function getRaw()
    {
        return implode(PHP_EOL, $this->raw);
    }
}
