<?php

namespace rokorolov\parus\gallery\commands;

/**
 * DeleteAlbumIntroImageCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteAlbumIntroImageCommand
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
