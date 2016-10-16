<?php

namespace rokorolov\parus\user\commands;

/**
 * AfterUserLoginCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AfterUserLoginCommand
{
    private $userId;
    
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
}
