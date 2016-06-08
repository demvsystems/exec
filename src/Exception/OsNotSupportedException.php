<?php

namespace Demv\Exec\Exception;

class OsNotSupportedExeception extends \Exception {

    public function __construct()
    {
        parent::__construct('This command isn\'t compatible with your current OS');
    }
}
