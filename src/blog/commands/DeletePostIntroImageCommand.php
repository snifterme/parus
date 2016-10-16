<?php

namespace rokorolov\parus\blog\commands;

/**
 * DeletePostIntroImageCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeletePostIntroImageCommand
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
