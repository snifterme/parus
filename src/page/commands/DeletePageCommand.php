<?php

namespace rokorolov\parus\page\commands;

/**
 * DeletePageCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeletePageCommand
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
