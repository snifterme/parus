<?php

namespace rokorolov\parus\blog\commands;

/**
 * DeleteCategoryIntroImageCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteCategoryIntroImageCommand
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
