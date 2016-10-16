<?php

namespace rokorolov\parus\language\commands;

/**
 * DeleteLanguageCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteLanguageCommand
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
