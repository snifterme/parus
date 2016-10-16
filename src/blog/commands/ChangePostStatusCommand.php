<?php

namespace rokorolov\parus\blog\commands;

/**
 * ChangePostStatusCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangePostStatusCommand
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
