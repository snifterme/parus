<?php

namespace rokorolov\parus\blog\commands;

/**
 * DeleteCategoryCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteCategoryCommand
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
