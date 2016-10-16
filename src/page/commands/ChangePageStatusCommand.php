<?php

namespace rokorolov\parus\page\commands;

/**
 * ChangePageStatusCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangePageStatusCommand
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
