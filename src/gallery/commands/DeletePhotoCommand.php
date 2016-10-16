<?php

namespace rokorolov\parus\gallery\commands;

/**
 * DeletePhotoCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeletePhotoCommand
{
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
