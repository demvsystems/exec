<?php

namespace Demv\Exec;

/**
 * Enum for possible OS restrictions of a command
 */
interface OS
{
    const ALL   = 'ALL';
    const LINUX = 'LIN';
    const WIN   = 'WIN';
}
