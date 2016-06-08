<?php

namespace Demv\Exec;

/**
 * Enum for possible OS restrictions of a command
 */
abstract class OS
{
    const ALL   = 'ALL';
    const LINUX = 'LIN';
    const WIN   = 'WIN';

    public static function getCurrentOs()
    {
        return strtoupper(substr(PHP_OS, 0, 3));
    }
}
