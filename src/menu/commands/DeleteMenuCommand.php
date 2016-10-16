<?php

namespace rokorolov\parus\menu\commands;

/**
 * DeleteMenuCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteMenuCommand
{
    public $model;
    
    private $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
}
