<?php

namespace Demv\Exec\Exception;

/**
 * Exception if a commands restricted OS isn't matching the executing OS
 */
class OsNoMatchException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            'PHP_OS:' .
            strtoupper(substr(PHP_OS, 0, 3)) .
            ' does not match the Commands OS'
        );
    }
}
