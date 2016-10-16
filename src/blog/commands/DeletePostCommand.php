<?php

namespace rokorolov\parus\blog\commands;

/**
 * DeletePostCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeletePostCommand
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
