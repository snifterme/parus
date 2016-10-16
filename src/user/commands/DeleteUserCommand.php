<?php

namespace rokorolov\parus\user\commands;

/**
 * DeleteUserCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteUserCommand
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
