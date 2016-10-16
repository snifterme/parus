<?php

namespace rokorolov\parus\gallery\commands;

/**
 * DeleteAlbumCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteAlbumCommand
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
