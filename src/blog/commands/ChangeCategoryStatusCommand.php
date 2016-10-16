<?php

namespace rokorolov\parus\blog\commands;

/**
 * ChangeCategoryStatusCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangeCategoryStatusCommand
{
    private $id;
    private $status;
    
    public function __construct($id, $status)
    {
        $this->id = $id;
        $this->status = $status;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
}
