<?php

error_reporting(E_ALL ^ E_STRICT);

if (version_compare(PHP_VERSION, '7.0', '<')) {
    define('USE_STRICT_TYPES', false);

    function type_hint($code, $error)
    {
        if (strpos($error, 'string, string') !== false) {
            return true;
        }

        if (strpos($error, 'int, integer') !== false) {
            return true;
        }

        if (strpos($error, 'bool, boolean') !== false) {
            return true;
        }

        if (strpos($error, 'float, double') !== false) {
            return true;
        }

        if (USE_STRICT_TYPES) {
            return false;
        }

        if (strpos($error, 'string, integer') !== false ||
            strpos($error, 'string, double') !== false ||
            strpos($error, 'string, boolean') !== false
        ) {
            return true;
        }

        if (strpos($error, 'int, string') !== false ||
            strpos($error, 'float, string') !== false ||
            strpos($error, 'bool, string') !== false
        ) {
            if (!preg_match('#^Argument (\d+)#', $error, $matches)) {
                return false;
            }

            $arg    = (int) $matches[1];
            $trace  = debug_backtrace();
            $args   = $trace[1]['args'];
            $values = array_values($args);

            return is_numeric($values[$arg - 1]);
        }

        return false;
    }

    set_error_handler('type_hint', E_RECOVERABLE_ERROR);
}
