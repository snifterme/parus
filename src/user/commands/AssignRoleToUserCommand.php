<?php

namespace rokorolov\parus\user\commands;

/**
 * AssignRoleToUserCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AssignRoleToUserCommand
{
    private $role;
    private $userId;
    
    public function __construct($role, $userId)
    {
        $this->role = $role;
        $this->userId = $userId;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
}
