<?php

namespace rokorolov\parus\gallery\commands;

/**
 * ChangePhotoStatusCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangePhotoStatusCommand
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
