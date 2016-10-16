<?php

namespace rokorolov\parus\menu\commands;

/**
 * DeleteMenuTypeCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteMenuTypeCommand
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
