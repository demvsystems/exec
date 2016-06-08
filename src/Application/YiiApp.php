<?php

namespace Demv\Exec\Application;

/**
 * Yii Application call
 */
class YiiApp extends App
{

    /**
     * Create a new Yii Application call with the command or application it belongs 
     * to 
     * 
     * @param Command|App $parent the command or application this application call 
     * belongs to
     */
    public function __construct($parent)
    {
        parent::__construct($parent, 'php console.php');
    }
}
