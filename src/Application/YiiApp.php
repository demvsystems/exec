<?php

namespace Demv\Exec\Application;

use Demv\Exec\Command;

/**
 * Yii Application call
 */
class YiiApp extends App
{

    /**
     * Create a new Yii Application call with the command it belongs to 
     * 
     * @param Command $command the command this application call belongs to
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
        $this->name = 'php console.php';
    }
}
